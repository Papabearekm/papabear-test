<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\CouponRedeem;
use App\Models\Individual;
use App\Models\Invoice;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ProductOrders;
use App\Models\User;
use App\Models\Salon;
use App\Models\Settings;
use Barryvdh\DomPDF\Facade\Pdf;
use Kreait\Firebase\Messaging\CloudMessage;
use Validator;
use DB;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;

class ProductOrdersController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'freelancer_id' => 'required',
            'salon_id' => 'required',
            'date_time' => 'required',
            'paid_method' => 'required',
            'order_to' => 'required',
            'orders' => 'required',
            'notes' => 'required',
            'total' => 'required',
            'tax' => 'required',
            'grand_total' => 'required',
            'discount' => 'required',
            'delivery_charge' => 'required',
            'extra' => 'required',
            'pay_key' => 'required',
            'status' => 'required',
            'payStatus' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        if($request->coupon_code != 'NA') {
            $coupon = json_decode($request->coupon_code);
            if($coupon) {
                CouponRedeem::create([
                    'uid' => $request->uid,
                    'coupon_id' => $coupon->id,
                ]);
            }
        }
        $data = ProductOrders::create($request->all());
        if (is_null($data)) {
            $response = [
                'data'=>$data,
                'message' => 'error',
                'status' => 500,
            ];
            return response()->json($response, 200);
        }
        if($request && $request->wallet_used == 1){ 
            $redeemer = User::where('id',$request->uid)->first();
            $redeemer->withdraw($request->wallet_price);
        }
        Notification::create([
            'uid' => $request->uid,
            'title' => "Product order placed",
            'message' => "Your Product order is placed successfully...",
            'type' => 'Product',
            'appointment_id' => $data->id
        ]);
        Notification::create([
            'uid' => $data->freelancer_id == 0 ? $data->salon_id : $data->freelancer_id,
            'title' => "Product order placed",
            'message' => "You have a new Product order...",
            'type' => 'Product',
            'appointment_id' => $data->id
        ]);
        try {
            $user = User::find($request->uid);
            if($user && $user->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => "Product Order Placed",
                        'body' => "Your order have been placed successfully..."
                    ]
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $user->fcm_token = null;
            $user->save();
            Log::error($e->getMessage());
        }
        try {
            $partner = User::find($data->freelancer_id == 0 ? $data->salon_id : $data->freelancer_id);
            if($partner && $partner->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $partner->fcm_token,
                    'notification' => [
                        'title' => "New Product Order",
                        'body' => "Your have a new product order..."
                    ]
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $partner->fcm_token = null;
            $partner->save();
            Log::error($e->getMessage());
        }
        $generalInfo = Settings::take(1)->first();
        $user = User::find($data->uid);
        $mail = $user->email;
        $username = $user->first_name . ' ' . $user->last_name;
        $subject = "Product Ordered";
        Mail::send(
            'mails/details',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Product',
                'date' => Carbon::parse($data->updated_at)->setTimezone('Asia/Kolkata')->format('d M Y'),
                'order_id' => $data->id,
                'business_name' => $data->freelancer_id == 0 ? Salon::where('uid', $data->salon_id)->first()->name : (User::find($data->freelancer_id)->first_name . ' ' . User::find($data->freelancer_id)->last_name),
                'status' => 'Order created',
                'messageType' => 'Customer'
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $mail = $partner->email;
        $username = $partner->first_name . ' ' . $partner->last_name;
        $subject = "New Product Order";
        Mail::send(
            'mails/details-partner',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Product',
                'date' => Carbon::parse($data->updated_at)->setTimezone('Asia/Kolkata')->format('d M Y'),
                'order_id' => $data->id,
                'messageType' => 'Partner',
                'business_name' => (User::find($data->uid)->first_name . ' ' . User::find($data->uid)->last_name),
                'status' => 'Order created',
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getById(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::find($request->id);
        $freelancerInfo  = User::select('id','first_name','last_name','cover')->where('id',$data->freelancer_id)->first();
        $data->freelancerInfo =$freelancerInfo;
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getOrderDetailsFromFreelancer(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::find($request->id);
        $userInfo  = User::where('id',$data->uid)->first();
        $data->userInfo =$userInfo;

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getFreelancerOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('freelancer_id',$request->id)->get();
        foreach($data as $loop){
            if($loop && $loop->uid && $loop->uid !=null){
                $loop->userInfo = User::where('id',$loop->uid)->first();
            }
        }
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = ProductOrders::find($request->id);
        $data->update($request->all());

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        if($request->status == 1) {
            $statusText = "accepted";
        } else if($request->status == 2) {
            $statusText = "rejected";
        } else if($request->status == 3) {
            $statusText = "ongoing";
        } else if($request->status == 4) {
            $statusText = "completed";
        } else if($request->status == 5) {
            $statusText = "cancelled";
        } else if($request->status == 6) {
            $statusText = "refunded";
        } else if($request->status == 7) {
            $statusText = "delayed";
        } else if($request->status == 8) {
            $statusText = "payment is pending";
        }
        // Update withdrawal_balance or cod_balance
        if ($request->status == 4) { // completed
            $grandTotal = (float) $data->grand_total;
            if ($data->paid_method == 5) { // Online order
                // Add to withdrawal_balance
                $user = User::find($data->freelancer_id == 0 ? $data->salon_id : $data->freelancer_id);
                if ($user) {
                    $user->withdrawal_balance = ($user->withdrawal_balance ?? 0) + $grandTotal;
                    $user->save();
                }
            } elseif ($data->paid_method == 1) { // COD
                // Add 5% of grand_total to cod_balance
                $user = User::find($data->freelancer_id == 0 ? $data->salon_id : $data->freelancer_id);
                if ($user) {
                    $user->withdrawal_balance = ($user->withdrawal_balance ?? 0) - round($grandTotal * 0.05, 2);
                    $user->cod_balance = ($user->cod_balance ?? 0) + round($grandTotal * 0.05, 2);
                    $user->save();
                }
            }
        }
        Notification::create([
            'uid' => $data->uid,
            'title' => "Product order " . $statusText,
            'message' => "Your Product order is " . $statusText . "...",
            'type' => 'Product',
            'appointment_id' => $data->id
        ]);
        Notification::create([
            'uid' => $data->freelancer_id == 0 ? $data->salon_id : $data->freelancer_id,
            'title' => "Product order " . $statusText,
            'message' => "Product order is " . $statusText,
            'type' => 'Product',
            'appointment_id' => $data->id
        ]);
        try {
            $user = User::find($data->uid);
            if($user && $user->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => "Product Order is " . $statusText,
                        'body' => "Your order is " . $statusText,
                    ]
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $user->fcm_token = null;
            $user->save();
            Log::error($e->getMessage());
        }
        try {
            $partner = User::find($data->freelancer_id == 0 ? $data->salon_id : $data->freelancer_id);
            if($partner && $partner->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $partner->fcm_token,
                    'notification' => [
                        'title' => "Product Order is " . $statusText,
                        'body' => "Your order is " . $statusText,
                    ]
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $partner->fcm_token = null;
            $partner->save();
            Log::error($e->getMessage());
        }
        $generalInfo = Settings::take(1)->first();
        $mail = $partner->email;
        $username = $partner->first_name . ' ' . $partner->last_name;
        $subject = "Order " . $statusText;
        Mail::send(
            'mails/details',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Product',
                'date' => Carbon::parse($data->updated_at)->setTimezone('Asia/Kolkata')->format('d M Y'),
                'order_id' => $data->id,
                'business_name' => (User::find($data->uid)->first_name . ' ' . User::find($data->uid)->last_name),
                'status' => $statusText,
                'messageType' => 'Partner'
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $mail = $user->email;
        $username = $user->first_name . ' ' . $user->last_name;
        $subject = "Order " . $statusText;
        Mail::send(
            'mails/details',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Product',
                'date' => Carbon::parse($data->updated_at)->setTimezone('Asia/Kolkata')->format('d M Y'),
                'order_id' => $data->id,
                'business_name' => $data->freelancer_id == 0 ? Salon::where('uid', $data->salon_id)->first()->name : (User::find($data->freelancer_id)->first_name . ' ' . User::find($data->freelancer_id)->last_name),
                'status' => $statusText,
                'messageType' => 'Customer'
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = ProductOrders::find($request->id);
        if ($data) {
            $data->delete();
            $response = [
                'data'=>$data,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'success' => false,
            'message' => 'Data not found.',
            'status' => 404
        ];
        return response()->json($response, 404);
    }

    public function getByUID(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('uid',$request->id)->orderBy('id','desc')->get();
        foreach($data as $loop){
            if($loop->freelancer_id !=0){
                $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$loop->freelancer_id)->first();
            }else{
                $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$loop->salon_id)->first();
            }
            $loop->type = $freelancerInfo->type;
            if($freelancerInfo->type == "individual"){
                $loop->freelancerInfo =$freelancerInfo;
            }else {
                $loop->salonInfo = Salon::select('name','cover','address')->where('uid',$loop->salon_id)->first();
            }
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getAllOrderAdmin(Request $request){
        $data = ProductOrders::orderBy('id','desc')->get();
        foreach($data as $loop){
            if($loop->freelancer_id !=0){
                $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$loop->freelancer_id)->first();
            }else{
                $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$loop->salon_id)->first();
            }
            $loop->type = $freelancerInfo->type;
            if($freelancerInfo->type == "individual"){
                $loop->freelancerInfo =$freelancerInfo;
            }else {
                $loop->salonInfo = Salon::select('name','cover','address')->where('uid',$loop->salon_id)->first();
            }
            $loop->userInfo = User::where('id',$loop->uid)->first();
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getIndividualOrders(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('freelancer_id',$request->id)->orderBy('id','desc')->get();
        foreach($data as $loop){
            $loop->freelancerInfo = User::select('id','first_name','last_name','cover','type')->where('id',$loop->freelancer_id)->first();
            $loop->type = $loop->freelancerInfo->type;
            $loop->userInfo = User::select('id','first_name','last_name','cover')->where('id',$loop->uid)->first();
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getSalonOrders(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('salon_id',$request->id)->orderBy('id','desc')->get();
        foreach($data as $loop){
            $loop->salonInfo = User::select('id','first_name','last_name','cover','type')->where('id',$loop->salon_id)->first();
            $loop->type = $loop->salonInfo->type;
            $loop->userInfo = User::select('id','first_name','last_name','cover')->where('id',$loop->uid)->first();
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('id',$request->id)->first();
        if($data->freelancer_id !=0){
            $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$data->freelancer_id)->first();
            $data->ownerInfo = User::select('first_name','last_name','email','mobile','country_code','fcm_token','cover')->where('id',$data->freelancer_id)->first();
        }else{
            $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$data->salon_id)->first();
            $data->ownerInfo = User::select('first_name','last_name','email','mobile','country_code','fcm_token','cover')->where('id',$data->salon_id)->first();

        }

        $data->type = $freelancerInfo->type;
        if($freelancerInfo->type == "individual"){
            $data->freelancerInfo =$freelancerInfo;
        }else {
            $data->salonInfo = Salon::select('name','cover','address')->where('uid',$data->salon_id)->first();
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getInfoOwner(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('id',$request->id)->first();
        $data->userInfo = User::where('id',$data->uid)->first();
        if($data->freelancer_id !=0){
            $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$data->freelancer_id)->first();
        }else{
            $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$data->salon_id)->first();
        }

        $data->type = $freelancerInfo->type;
        if($freelancerInfo->type == "individual"){
            $data->freelancerInfo =$freelancerInfo;
        }else {
            $data->salonInfo = Salon::select('name','cover','address')->where('uid',$data->salon_id)->first();
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getInfoAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = ProductOrders::where('id',$request->id)->first();
        $data->userInfo = User::where('id',$data->uid)->first();
        if($data->freelancer_id !=0){
            $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$data->freelancer_id)->first();
            $data->ownerInfo = User::select('first_name','last_name','email','mobile','country_code','fcm_token','cover')->where('id',$data->freelancer_id)->first();
        }else{
            $freelancerInfo  = User::select('id','first_name','last_name','cover','type')->where('id',$data->salon_id)->first();
            $data->ownerInfo = User::select('first_name','last_name','email','mobile','country_code','fcm_token','cover')->where('id',$data->salon_id)->first();
        }

        $data->type = $freelancerInfo->type;
        if($freelancerInfo->type == "individual"){
            $data->freelancerInfo =$freelancerInfo;
        }else {
            $data->salonInfo = Salon::select('name','cover','address')->where('uid',$data->salon_id)->first();
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getStats(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'month'=>'required',
            'year'=>'required',
            'type'=>'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        if($request->type =='individual'){
            $monthData = ProductOrders::select(\DB::raw("COUNT(*) as count"), \DB::raw("DATE(created_at) as day_name"), \DB::raw("DATE(created_at) as day"),\DB::raw('ROUND(SUM(grand_total),2) AS total'),\DB::raw('ROUND(SUM(CASE WHEN paid_method = 1 THEN grand_total ELSE 0 END),2) AS COD_total'), \DB::raw('ROUND(SUM(CASE WHEN paid_method = 5 THEN grand_total ELSE 0 END),2) AS Online_total'))
                ->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year)
                ->groupBy('day_name','day')
                ->orderBy('day')
                ->where('freelancer_id',$request->id)
                ->where('status', 4)
                ->get();
        }

        if($request->type =='salon'){
            $monthData = ProductOrders::select(\DB::raw("COUNT(*) as count"), \DB::raw("DATE(created_at) as day_name"), \DB::raw("DATE(created_at) as day"),\DB::raw('ROUND(SUM(grand_total),2) AS total'),\DB::raw('ROUND(SUM(CASE WHEN paid_method = 1 THEN grand_total ELSE 0 END),2) AS COD_total'), \DB::raw('ROUND(SUM(CASE WHEN paid_method = 5 THEN grand_total ELSE 0 END),2) AS Online_total'))
                ->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year)
                ->groupBy('day_name','day')
                ->orderBy('day')
                ->where('salon_id',$request->id)
                ->where('status', 4)
                ->get();
        }
        $monthData = $monthData->map(function ($item) {
            $item->total = (float)number_format((float)$item->total, 2, '.', '');
            $item->COD_total = (float)number_format((float)$item->COD_total, 2, '.', '');
            $item->Online_total = (float)number_format((float)$item->Online_total, 2, '.', '');
            return $item;
        });
        $monthResponse = [];
        foreach($monthData as $row) {
            $monthResponse['label'][] = date('l, d',strtotime($row->day_name));
            $monthResponse['data'][] = (int) $row->count;
            $monthResponse['total'][] = $row->total;
        }
        if(isset($monthData) && count($monthData)>0){
            $response = [
                'data'=>$monthData,
                'chart' => $monthResponse,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'data'=>[],
                'chart' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function getMonthsStats(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'year'=>'required',
            'type'=>'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        if($request->type =='individual'){
            $monthData = ProductOrders::select(\DB::raw("COUNT(*) as count"), \DB::raw("MONTH(created_at) as day_name"), \DB::raw("MONTH(created_at) as day"),\DB::raw('ROUND(SUM(grand_total),2) AS total'), \DB::raw('ROUND(SUM(CASE WHEN paid_method = 1 THEN grand_total ELSE 0 END),2) AS COD_total'), \DB::raw('ROUND(SUM(CASE WHEN paid_method = 5 THEN grand_total ELSE 0 END),2) AS Online_total'))
                ->whereYear('created_at', $request->year)
                ->groupBy('day_name','day')
                ->orderBy('day')
                ->where('freelancer_id',$request->id)
                ->where('status', 4)
                ->get();
        }

        if($request->type =='salon'){
            $monthData = ProductOrders::select(\DB::raw("COUNT(*) as count"), \DB::raw("MONTH(created_at) as day_name"), \DB::raw("MONTH(created_at) as day"),\DB::raw('ROUND(SUM(grand_total),2) AS total'), \DB::raw('ROUND(SUM(CASE WHEN paid_method = 1 THEN grand_total ELSE 0 END),2) AS COD_total'), \DB::raw('ROUND(SUM(CASE WHEN paid_method = 5 THEN grand_total ELSE 0 END),2) AS Online_total'))
                ->whereYear('created_at', $request->year)
                ->groupBy('day_name','day')
                ->orderBy('day')
                ->where('salon_id',$request->id)
                ->where('status', 4)
                ->get();
        }
        $monthData = $monthData->map(function ($item) {
            $item->total = (float)number_format((float)$item->total, 2, '.', '');
            $item->COD_total = (float)number_format((float)$item->COD_total, 2, '.', '');
            $item->Online_total = (float)number_format((float)$item->Online_total, 2, '.', '');
            return $item;
        });
        $monthResponse = [];
        foreach($monthData as $row) {
            $monthResponse['label'][] = date('F', mktime(0, 0, 0, $row->day_name, 10));
            $monthResponse['data'][] = (int) $row->count;
            $monthResponse['total'][] = $row->total;
        }
        if(isset($monthData) && count($monthData)>0){
            $response = [
                'data'=>$monthData,
                'chart' => $monthResponse,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'data'=>[],
                'chart' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function getAllStats(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        if($request->type =='individual'){
            $monthData = ProductOrders::select(\DB::raw("COUNT(*) as count"), \DB::raw("DATE_FORMAT(created_at, '%Y') day_name"), \DB::raw("YEAR(created_at) as day"),\DB::raw('ROUND(SUM(grand_total),2) AS total'),\DB::raw('ROUND(SUM(CASE WHEN paid_method = 1 THEN grand_total ELSE 0 END),2) AS COD_total'),\DB::raw('ROUND(SUM(CASE WHEN paid_method = 5 THEN grand_total ELSE 0 END),2) AS Online_total'))
                ->groupBy('day_name','day')
                ->orderBy('day')
                ->where('freelancer_id',$request->id)
                ->where('status', 4)
                ->get();
        }

        if($request->type =='salon'){
            $monthData = ProductOrders::select(\DB::raw("COUNT(*) as count"), \DB::raw("DATE_FORMAT(created_at, '%Y') day_name"), \DB::raw("YEAR(created_at) as day"),\DB::raw('ROUND(SUM(grand_total),2) AS total'),\DB::raw('ROUND(SUM(CASE WHEN paid_method = 1 THEN grand_total ELSE 0 END),2) AS COD_total'),\DB::raw('ROUND(SUM(CASE WHEN paid_method = 5 THEN grand_total ELSE 0 END),2) AS Online_total'))
                ->groupBy('day_name','day')
                ->orderBy('day')
                ->where('salon_id',$request->id)
                ->where('status', 4)
                ->get();
        }
        $monthData = $monthData->map(function ($item) {
            $item->total = (float)number_format((float)$item->total, 2, '.', '');
            $item->COD_total = (float)number_format((float)$item->COD_total, 2, '.', '');
            $item->Online_total = (float)number_format((float)$item->Online_total, 2, '.', '');
            return $item;
        });
        $monthResponse = [];
        foreach($monthData as $row) {
            $monthResponse['label'][] = date('Y', strtotime($row->day_name));
            $monthResponse['data'][] = (int) $row->count;
            $monthResponse['total'][] = $row->total;
        }
        if(isset($monthData) && count($monthData)>0){
            $response = [
                'data'=>$monthData,
                'chart' => $monthResponse,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'data'=>[],
                'chart' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function printInvoice(Request $request){
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'token'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        try {
            $papabearGST = '32DCOPA4075A1Z4';
            $papabearPAN = 'DCOPA4075A';
            $papabearAddress = 'Papa Bear, Unique Beauty Solution, Njanickal Building, Brahmapuram P.O, Ernakulam, Kerala-India, 682303';
            $data = ProductOrders::find($request->id);
            $customer = User::find($data->uid);
            $customerName = $customer->first_name . ' ' . $customer->last_name;
            $customerAddress = $data->order_to == "home" ? json_decode($data->address) : '';
            $papabearInvoice = Invoice::where('product_id', $data->id)->where('type', 'Host')->first();
            $partnerInvoice = Invoice::where('product_id', $data->id)->where('type', 'Partner')->first();
            $today = Carbon::today();
            if ($today->month >= 4) {
                $startYear = $today->year;
                $endYear = $today->year + 1;
            } else {
                $startYear = $today->year - 1;
                $endYear = $today->year;
            }
            $fiscalYear = substr($startYear, -2) . '-' . substr($endYear, -2);
            $papabearTotalInvoice = Invoice::where('type', 'Host')->where('fiscal_year', $fiscalYear)->count();
            $invoiceNumber = str_pad($papabearTotalInvoice + 1, 4, '0', STR_PAD_LEFT);
            if (is_null($papabearInvoice)) {
                $papabearInvoice = new Invoice();
                $papabearInvoice->invoice_number = 'PB-' . $invoiceNumber . '/' . $fiscalYear;
                $papabearInvoice->invoice_date = Carbon::parse($data->created_at)->format('d-m-Y');
                $papabearInvoice->product_id = $data->id;
                $papabearInvoice->fiscal_year = $fiscalYear;
                $papabearInvoice->type = 'Host';
                $papabearInvoice->save();
            }
            if ($data->freelancer_id == 0) {
                $partnerInformation = Salon::where('uid', $data->salon_id)->first();
                $partnerUserInformation = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->salon_id)->first();
                $sellerName = $partnerInformation->name;
            } else {
                $partnerInformation = Individual::select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                    ->join('users', 'individual.uid', 'users.id')
                    ->where('individual.uid', $data->freelancer_id)
                    ->first();
                $partnerUserInformation = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->freelancer_id)->first();
                $sellerName = $partnerInformation->first_name . ' ' . $partnerInformation->last_name;
            }
            $sellerAddress = $partnerInformation->address;
            $sellerPan = $partnerInformation->pan;
            $sellerGST = $partnerInformation->vat;
            $papabearInvoiceNumber = $papabearInvoice ? $papabearInvoice->invoice_number : 'PB-' . $invoiceNumber . '/' . $fiscalYear;
            $partnerTotalInvoice = Invoice::where('type', 'Partner')->where('fiscal_year', $fiscalYear)->where('partner_id', $partnerInformation->uid)->count();
            $paddedInvoiceNumber = $partnerTotalInvoice + 1;
            $sellerInvoiceNumber = $partnerInvoice ? $partnerInvoice->invoice_number : $partnerInformation->invoice_prefix . $paddedInvoiceNumber . '/' . $fiscalYear;
            $invoiceDate = $papabearInvoice ? $papabearInvoice->invoice_date : Carbon::parse($data->created_at)->format('d-m-Y');
            if (is_null($partnerInvoice)) {
                $partnerInvoice = new Invoice();
                $partnerInvoice->invoice_number = $sellerInvoiceNumber;
                $partnerInvoice->invoice_date = Carbon::parse($data->created_at)->format('d-m-Y');
                $partnerInvoice->product_id = $data->id;
                $partnerInvoice->partner_id = $partnerInformation->uid;
                $partnerInvoice->type = 'Partner';
                $partnerInvoice->fiscal_year = $fiscalYear;
                $partnerInvoice->save();
            }
            $items['products'] = json_decode($data->orders, true);

            $items['grandTotal'] = (float)$data->grand_total;
            $items['discount'] = (float)$data->discount ?? 0;
            $items['wallet'] = (float)$data->wallet_price ?? 0;
            $items['total_in_words'] = $this->convertNumberToWords($data->grand_total);

            // Distance charge (show tax, don’t add to grandTotal)
            $distanceCharge = isset($data->delivery_charge) ? (float) $data->delivery_charge : 0;
            if ($distanceCharge > 0) {
                $distanceTax = $distanceCharge * 0.18;
                $items['distance_cost'] = (float) $distanceCharge;
                $items['distance_cost_tax'] = (float) $distanceTax;
            } else {
                $items['distance_cost'] = null;
                $items['distance_cost_tax'] = null;
            }
            $html = view('papabear-product-invoice', compact(
                'papabearGST',
                'papabearPAN',
                'papabearAddress',
                'customerName',
                'customerAddress',
                'papabearInvoiceNumber',
                'invoiceDate',
                'sellerName',
                'sellerAddress',
                'sellerPan',
                'sellerGST',
                'sellerInvoiceNumber',
                'items'
            ))->render();
            $pdf = Pdf::loadView('papabear-product-invoice', compact(
                'papabearGST',
                'papabearPAN',
                'papabearAddress',
                'customerName',
                'customerAddress',
                'papabearInvoiceNumber',
                'invoiceDate',
                'sellerName',
                'sellerAddress',
                'sellerPan',
                'sellerGST',
                'sellerInvoiceNumber',
                'items'
            ))->setPaper('a4', 'portrait')->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
            $sanitizedFileName = str_replace(['/', '\\'], '-', $sellerInvoiceNumber) . '.pdf';
            if ($request->query('type') == 'download') {
                return $pdf->download($sanitizedFileName);
            }
            if ($request->query('type', 'display') == 'display') {
                return view('papabear-invoice-display', [
                    'pdf' => $html,
                    'invoiceNumber' => $sanitizedFileName
                ]);
            }
            return $pdf->stream($sanitizedFileName);
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);

        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 401);

        }
    }

    public function printCommissionInvoice(Request $request){
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'token'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        try {
            $papabearGST = '32DCOPA4075A1Z4';
            $papabearPAN = 'DCOPA4075A';
            $papabearAddress = 'Papa Bear, Unique Beauty Solution, Njanickal Building, Brahmapuram P.O, Ernakulam, Kerala-India, 682303';
            $data = ProductOrders::find($request->id);
            $customer = User::find($data->uid);
            $customerName = $customer->first_name . ' ' . $customer->last_name;
            $customerAddress = $data->order_to == "home" ? json_decode($data->address) : '';
            $papabearInvoice = Invoice::where('product_id', $data->id)->where('type', 'Host')->first();
            $partnerInvoice = Invoice::where('product_id', $data->id)->where('type', 'Partner')->first();
            $today = Carbon::today();
            if ($today->month >= 4) {
                $startYear = $today->year;
                $endYear = $today->year + 1;
            } else {
                $startYear = $today->year - 1;
                $endYear = $today->year;
            }
            $fiscalYear = substr($startYear, -2) . '-' . substr($endYear, -2);
            $papabearTotalInvoice = Invoice::where('type', 'Host')->where('fiscal_year', $fiscalYear)->count();
            $invoiceNumber = str_pad($papabearTotalInvoice + 1, 4, '0', STR_PAD_LEFT);
            if (is_null($papabearInvoice)) {
                $papabearInvoice = new Invoice();
                $papabearInvoice->invoice_number = 'PB-' . $invoiceNumber . '/' . $fiscalYear;
                $papabearInvoice->invoice_date = Carbon::parse($data->created_at)->format('d-m-Y');
                $papabearInvoice->product_id = $data->id;
                $papabearInvoice->fiscal_year = $fiscalYear;
                $papabearInvoice->type = 'Host';
                $papabearInvoice->save();
            }
            if ($data->freelancer_id == 0) {
                $partnerInformation = Salon::where('uid', $data->salon_id)->first();
                $partnerUserInformation = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->salon_id)->first();
                $sellerName = $partnerInformation->name;
            } else {
                $partnerInformation = Individual::select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                    ->join('users', 'individual.uid', 'users.id')
                    ->where('individual.uid', $data->freelancer_id)
                    ->first();
                $partnerUserInformation = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->freelancer_id)->first();
                $sellerName = $partnerInformation->first_name . ' ' . $partnerInformation->last_name;
            }
            $sellerAddress = $partnerInformation->address;
            $sellerPan = $partnerInformation->pan;
            $sellerGST = $partnerInformation->vat;
            $papabearInvoiceNumber = $papabearInvoice ? $papabearInvoice->invoice_number : 'PB-' . $invoiceNumber . '/' . $fiscalYear;
            $partnerTotalInvoice = Invoice::where('type', 'Partner')->where('fiscal_year', $fiscalYear)->where('partner_id', $partnerInformation->uid)->count();
            $paddedInvoiceNumber = $partnerTotalInvoice + 1;
            $sellerInvoiceNumber = $partnerInvoice ? $partnerInvoice->invoice_number : $partnerInformation->invoice_prefix . $paddedInvoiceNumber . '/' . $fiscalYear;
            $invoiceDate = $papabearInvoice ? $papabearInvoice->invoice_date : Carbon::parse($data->created_at)->format('d-m-Y');
            if (is_null($partnerInvoice)) {
                $partnerInvoice = new Invoice();
                $partnerInvoice->invoice_number = $sellerInvoiceNumber;
                $partnerInvoice->invoice_date = Carbon::parse($data->created_at)->format('d-m-Y');
                $partnerInvoice->product_id = $data->id;
                $partnerInvoice->partner_id = $partnerInformation->uid;
                $partnerInvoice->type = 'Partner';
                $partnerInvoice->fiscal_year = $fiscalYear;
                $partnerInvoice->save();
            }
            $grandTotal = $data->grand_total;
            $settings = Settings::first();
            $commissionPercentage = ($settings->commission_percentage / 100) ?? 0;
            $commission = $grandTotal * $commissionPercentage;
            $commissionTotal = number_format(($commission + ($commission * 0.18)), 2);
            $amountInWords = $this->convertNumberToWords($commissionTotal);

            $html = view('papabear-invoice-commission', compact(
                'papabearGST',
                'papabearPAN',
                'papabearAddress',
                'customerName',
                'customerAddress',
                'papabearInvoiceNumber',
                'invoiceDate',
                'sellerName',
                'sellerAddress',
                'sellerPan',
                'sellerGST',
                'sellerInvoiceNumber',
                'commission',
                'amountInWords'
            ))->render();
            $pdf = Pdf::loadView('papabear-invoice-commission', compact(
                'papabearGST',
                'papabearPAN',
                'papabearAddress',
                'customerName',
                'customerAddress',
                'papabearInvoiceNumber',
                'invoiceDate',
                'sellerName',
                'sellerAddress',
                'sellerPan',
                'sellerGST',
                'sellerInvoiceNumber',
                'commission',
                'amountInWords'
            ))->setPaper('a4', 'portrait')->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
            $sanitizedFileName = str_replace(['/', '\\'], '-', $papabearInvoiceNumber) . '.pdf';
            if ($request->query('type') == 'download') {
                return $pdf->download($sanitizedFileName);
            }
            if ($request->query('type', 'display') == 'display') {
                return view('papabear-invoice-display', [
                    'pdf' => $html,
                    'invoiceNumber' => $sanitizedFileName
                ]);
            }
            return $pdf->stream($sanitizedFileName);
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);

        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 401);

        }
    }

    public function orderInvoice(Request $request){
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'token'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        try {

            $data = ProductOrders::where('id',$request->id)->first();
            $data->userInfo = User::where('id',$data->uid)->first();
            if($data->freelancer_id !=0){
                $freelancerInfo  = User::select('id','first_name','last_name','cover','type','email','mobile')->where('id',$data->freelancer_id)->first();
            }else{
                $freelancerInfo  = User::select('id','first_name','last_name','cover','type','email','mobile')->where('id',$data->salon_id)->first();
            }

            $data->type = $freelancerInfo->type;
            $data->freelancerInfo =$freelancerInfo;
            $data->salonInfo = Salon::select('name','cover','address')->where('uid',$data->salon_id)->first();
            $general = Settings::first();
            $addres ='';
            $addres = json_decode($data->address);

            $paymentName  = [
                'NA',
                'COD',
                'Stripe',
                'PayPal',
                'Paytm',
                'Razorpay',
                'Instamojo',
                'Paystack',
                'Flutterwave'
            ];
            $data->paid_method = $paymentName[$data->paid_method];
            $data->orders = json_decode($data->orders);
            $general->social = json_decode($general->social);
            $response = [
                'data'=>$data,
                'email'=>$general->email,
                'general'=>$general,
                'delivery'=>$addres
            ];
            // echo json_encode($data);
            return view('product-order',$response);
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);

        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 401);

        }
    }

    public function getOrderStats(Request $request){
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'from'     => 'required',
            'to'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $from = date($request->from);
        $to = date($request->to);
        $data = ProductOrders::whereRaw('FIND_IN_SET("'.$request->id.'",freelancer_id)')->orWhereRaw('FIND_IN_SET("'.$request->id.'",salon_id)')->whereBetween('date_time',[$from, $to])->where('status',4)->orderBy('id','desc')->get();
        $commission = DB::table('commission')->select('rate')->where('uid',$request->id)->first();
        $response = [
            'data'=>$data,
            'commission'=>$commission,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    private function convertNumberToWords($number)
    {
        $no = floor((float)$number);
        $point = round((float)$number - $no, 2) * 100;

        $words = [
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        ];

        $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
        $str = [];

        $digits_1 = ['', '', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
        $i = 0;

        if ($no == 0) {
            $str[] = "Zero";
        }

        while ($no > 0) {
            if ($i == 1) {
                $divider = 10;
            } elseif ($i == 2) {
                $divider = 100;
            } else {
                $divider = 100;
            }

            $number = $no % $divider;
            $no = (int)($no / $divider);

            if ($number) {
                $plural = (count($str) && $number > 9) ? '' : null;
                $hundred = ($i == 2 && count($str)) ? ' and ' : null;
                if ($number < 21) {
                    $str[] = $words[$number] . " " . $digits[$i] . $plural;
                } else {
                    $str[] = $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$i] . $plural;
                }
            }
            $i++;
        }

        $result = implode(' ', array_reverse($str));
        $result = preg_replace('/\s+/', ' ', $result); // clean up extra spaces
        $rupees = trim($result) . " Rupees";

        if ($point > 0) {
            $points = ($point < 21)
                ? $words[$point]
                : $words[floor($point / 10) * 10] . " " . $words[$point % 10];
            return $rupees . " and " . trim($points) . " Paise Only";
        } else {
            return $rupees . " Only";
        }
    }
}
