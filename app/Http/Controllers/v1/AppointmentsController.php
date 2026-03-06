<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Livewire\Freelancer\Appointment;
use App\Models\CouponRedeem;
use Illuminate\Http\Request;
use App\Models\Appointments;
use App\Models\AppointmentCompletion;
use App\Models\Category;
use App\Models\Salon;
use App\Models\Individual;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Services;
use App\Models\SalonService;
use App\Models\User;
use App\Models\Settings;
use App\Models\Specialist;
use App\Models\Timeslots;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Kreait\Firebase\Messaging\CloudMessage;
use Validator;
use DB;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;

class AppointmentsController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'freelancer_id' => 'required',
            'salon_id' => 'required',
            'specialist_id' => 'required',
            'appointments_to' => 'required',
            'address' => 'required',
            'items' => 'required',
            'coupon_id' => 'required',
            'coupon' => 'required',
            'discount' => 'required',
            'distance_cost' => 'required',
            'total' => 'required',
            'serviceTax' => 'required',
            'grand_total' => 'required',
            'pay_method' => 'required',
            'paid' => 'required',
            'save_date' => 'required',
            'slot' => 'required',
            'wallet_used' => 'required',
            'wallet_price' => 'required',
            'notes' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        if($request->coupon_id != 0) {
            CouponRedeem::create([
                'uid' => $request->uid,
                'coupon_id' => $request->coupon_id,
            ]);
        }
        $data = Appointments::create($request->all());
        $user = Salon::where('uid', $data->salon_id)->first();
        if (is_null($user)) {
            $user = User::find($data->freelancer_id);
        }
        Notification::create([
            'uid' => $request->uid,
            'title' => "Appointment Booked",
            'message' => "Your appointment with " . $user->name . " have been booked successfully for date " . $request->save_date . "...",
            'type' => 'Appointment',
            'appointment_id' => $data->id
        ]);
        Notification::create([
            'uid' => $request->freelancer_id == 0 ? $request->salon_id : $request->freelancer_id,
            'title' => "Appointment Booked",
            'message' => "You have a new appointment for date " . $request->save_date,
            'type' => 'Appointment',
            'appointment_id' => $data->id
        ]);
        try {
            $user = User::find($request->uid);
            if ($user && $user->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => "Appointment Booked",
                        'body' => "Your appointment booking is successful..."
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
            $partner = User::find($request->freelancer_id == 0 ? $request->salon_id : $request->freelancer_id);
            if ($partner && $partner->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $partner->fcm_token,
                    'notification' => [
                        'title' => "New Appointment",
                        'body' => "You have a new appointment..."
                    ]
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $partner->fcm_token = null;
            $partner->save();
            Log::error($e->getMessage());
        }
        if (is_null($data)) {
            $response = [
                'data' => $data,
                'message' => 'error',
                'status' => 500,
            ];
            return response()->json($response, 200);
        }
        if ($request && $request->wallet_used == 1) {
            $redeemer = User::where('id', $request->uid)->first();
            $redeemer->withdraw($request->wallet_price);
        }
        $generalInfo = Settings::take(1)->first();
        $user = User::find($data->uid);
        $mail = $user->email;
        $username = $user->first_name . ' ' . $user->last_name;
        $subject = "Appointment Created";
        Mail::send(
            'mails/details',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Appointment',
                'date' => $data->save_date,
                'order_id' => $data->id,
                'messageType' => 'Customer',
                'business_name' => $data->freelancer_id == 0 ? Salon::where('uid', $data->salon_id)->first()->name : (User::find($data->freelancer_id)->first_name . ' ' . User::find($data->freelancer_id)->last_name),
                'status' => 'Created',
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $mail = $partner->email;
        $username = $partner->first_name . ' ' . $partner->last_name;
        $subject = "Appointment Created";
        Mail::send(
            'mails/details-partner',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Appointment',
                'date' => $data->save_date,
                'order_id' => $data->id,
                'messageType' => 'Partner',
                'business_name' => $user->first_name . ' ' . $user->last_name,
                'status' => 'Created'
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }

        $data = Appointments::find($request->id);

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::find($request->id)->update($request->all());

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        $appointment = Appointments::find($request->id);
        if ($appointment->status == 1) {
            $statusText = "accepted";
        } else if ($appointment->status == 2) {
            $statusText = "declined";
        } else if ($appointment->status == 3) {
            $statusText = "ongoing";
        } else if ($appointment->status == 4) {
            $statusText = "completed";
        } else if ($appointment->status == 5) {
            $statusText = "cancelled by user";
        } else if ($appointment->status == 6) {
            $statusText = "refunded";
        } else if ($appointment->status == 7) {
            $statusText = "delayed";
        } else if ($appointment->status == 8) {
            $statusText = "pending payment";
        } else {
            $statusText = 'updated';
        }
        $freelancer = Salon::where('uid', $appointment->salon_id)->first();
        if (is_null($freelancer)) {
            $freelancer = User::find($appointment->freelancer_id);
        }
        Notification::create([
            'uid' => $appointment->uid,
            'title' => "Appointment " . $statusText,
            'message' => "Your appointment with " . ($appointment->freelancer_id == 0 ? $freelancer->name : $freelancer->first_name . ' ' . $freelancer->last_name) . "is " . $statusText . "...",
            'type' => 'Appointment',
            'appointment_id' => $appointment->id
        ]);
        Notification::create([
            'uid' => $appointment->freelancer_id == 0 ? $appointment->salon_id : $appointment->freelancer_id,
            'title' => "Appointment " . $statusText,
            'message' => "Your appointment is " . $statusText,
            'type' => 'Appointment',
            'appointment_id' => $appointment->id
        ]);
        try {
            $user = User::find($appointment->uid);
            if ($user && $user->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => "Appointment " . $statusText,
                        'body' => "Your appointment is " . $statusText . "..."
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
            $partner = User::find($appointment->freelancer_id == 0 ? $appointment->salon_id : $appointment->freelancer_id);
            if ($partner && $partner->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $partner->fcm_token,
                    'notification' => [
                        'title' => "Appointment " . $statusText,
                        'body' => "Your appointment is " . $statusText . "..."
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
        $subject = "Appointment " . $statusText;
        Mail::send(
            'mails/details-partner',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Appointment',
                'date' => $appointment->save_date,
                'order_id' => $appointment->id,
                'business_name' => $user->first_name . ' ' . $user->last_name,
                'messageType' => 'Partner',
                'status' => $statusText,
            ],
            function ($message) use ($mail, $username, $subject, $generalInfo) {
                $message->to($mail, $username)
                    ->subject($subject);
                $message->from($generalInfo->email, $generalInfo->name);
            }
        );
        $mail = $user->email;
        $username = $user->first_name . ' ' . $user->last_name;
        $subject = "Appointment " . $statusText;
        Mail::send(
            'mails/details',
            [
                'app_name'      => $generalInfo->name,
                'orderType' => 'Appointment',
                'date' => $appointment->save_date,
                'order_id' => $appointment->id,
                'business_name' => $appointment->freelancer_id == 0 ? $freelancer->name : $freelancer->first_name . ' ' . $freelancer->last_name,
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
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::find($request->id);
        if ($data) {
            $data->delete();
            $response = [
                'data' => $data,
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

    public function getAll()
    {
        $data = Appointments::all();
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getAllSalonAppointments(Request $request)
    {
        $data = Appointments::where('salon_id', '!=', 0)->orderBy('id', 'desc')->get();
        foreach ($data as $loop) {
            $loop->salonInfo = Salon::where('uid', $loop->salon_id)->first();
            $loop->userInfo = User::select('id', 'first_name', 'last_name', 'cover')->where('id', $loop->uid)->first();
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getAllFreelancerAppointments(Request $request)
    {
        $data = Appointments::where('freelancer_id', '!=', 0)->orderBy('id', 'desc')->get();
        foreach ($data as $loop) {
            $loop->individualInfo = DB::table('individual')
                ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                ->join('users', 'individual.uid', 'users.id')
                ->where('individual.uid', $loop->freelancer_id)
                ->first();
            $loop->userInfo = User::select('id', 'first_name', 'last_name', 'cover')->where('id', $loop->uid)->first();
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getMyList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::where('uid', $request->id)->orderBy('id', 'desc')->get();
        foreach ($data as $loop) {
            if ($loop->freelancer_id == 0) {
                $loop->salonInfo = Salon::where('uid', $loop->salon_id)->first();
            } else {
                $loop->individualInfo = DB::table('individual')
                    ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                    ->join('users', 'individual.uid', 'users.id')
                    ->where('individual.uid', $loop->freelancer_id)
                    ->first();
            }
            $decodedItems = json_decode($loop->items);
            $decodedServices = $decodedItems->services;
            foreach ($decodedServices as $ser) {
                $service = Services::find($ser->service_id);
                if ($service) {
                    $ser->cate_id = $service->cate_id;
                    $categoryInfo = Category::find($service->cate_id);
                    if ($categoryInfo) {
                        $ser->web_cates_data = $categoryInfo;
                    }
                }
            }
            $loop->items = json_encode($decodedItems);
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getSalonList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::where('salon_id', $request->id)->orderBy('id', 'desc')->get();
        foreach ($data as $loop) {
            $loop->salonInfo = Salon::where('uid', $loop->salon_id)->first();
            $loop->userInfo = User::select('id', 'first_name', 'last_name', 'cover')->where('id', $loop->uid)->first();
            $decodedItems = json_decode($loop->items);
            $decodedServices = $decodedItems->services;
            foreach ($decodedServices as $ser) {
                $service = Services::find($ser->service_id);
                if ($service) {
                    $ser->cate_id = $service->cate_id;
                    $categoryInfo = Category::find($service->cate_id);
                    if ($categoryInfo) {
                        $ser->web_cates_data = $categoryInfo;
                    }
                }
            }
            $loop->items = json_encode($decodedItems);
            // $slot = Timeslots::find($loop->slot);
            // if($slot){
            //     $slotTimes = json_decode($slot->slots);
            //     $loop->slot = $slotTimes[0]->start_time . " - " . $slotTimes[0]->end_time;
            // }
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getIndividualList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::where('freelancer_id', $request->id)->orderBy('id', 'desc')->get();
        foreach ($data as $loop) {
            $loop->individualInfo = DB::table('individual')
                ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                ->join('users', 'individual.uid', 'users.id')
                ->where('individual.uid', $loop->freelancer_id)
                ->first();
            $loop->userInfo = User::select('id', 'first_name', 'last_name', 'cover')->where('id', $loop->uid)->first();
            $decodedItems = json_decode($loop->items);
            $decodedServices = $decodedItems->services;
            foreach ($decodedServices as $ser) {
                $service = Services::find($ser->service_id);
                if ($service) {
                    $ser->cate_id = $service->cate_id;
                    $categoryInfo = Category::find($service->cate_id);
                    if ($categoryInfo) {
                        $ser->web_cates_data = $categoryInfo;
                    }
                }
            }
            $loop->items = json_encode($decodedItems);
            $slot = Timeslots::find($loop->slot);
            if ($slot) {
                $slotTimes = json_decode($slot->slots);
                $loop->slot = $slotTimes[0]->start_time . " - " . $slotTimes[0]->end_time;
            }
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::find($request->id);
        $decodedItems = json_decode($data->items);
        $decodedServices = $decodedItems->services;
        foreach ($decodedServices as $ser) {
            $service = Services::find($ser->service_id);
            if ($service) {
                $ser->cate_id = $service->cate_id;
                $categoryInfo = Category::find($service->cate_id);
                if ($categoryInfo) {
                    $ser->web_cates_data = $categoryInfo;
                }
            }
        }
        $data->items = json_encode($decodedItems);
        if ($data->freelancer_id == 0) {
            $data->salonInfo = Salon::where('uid', $data->salon_id)->first();
            $data->ownerInfo = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->salon_id)->first();
        } else {
            $data->individualInfo = DB::table('individual')
                ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                ->join('users', 'individual.uid', 'users.id')
                ->where('individual.uid', $data->freelancer_id)
                ->first();
            $data->ownerInfo = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->freelancer_id)->first();
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getInfoAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::find($request->id);
        $data->userInfo = User::where('id', $data->uid)->first();
        if ($data->freelancer_id == 0) {
            $data->salonInfo = Salon::where('uid', $data->salon_id)->first();
            $data->ownerInfo = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->salon_id)->first();
        } else {
            $data->individualInfo = DB::table('individual')
                ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                ->join('users', 'individual.uid', 'users.id')
                ->where('individual.uid', $data->freelancer_id)
                ->first();
            $data->ownerInfo = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->freelancer_id)->first();
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getInfoOwner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::find($request->id);
        $data->userInfo = User::where('id', $data->uid)->first();
        if ($data->freelancer_id == 0) {
            $data->salonInfo = Salon::where('uid', $data->salon_id)->first();
        } else {
            $data->individualInfo = DB::table('individual')
                ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                ->join('users', 'individual.uid', 'users.id')
                ->where('individual.uid', $data->freelancer_id)
                ->first();
        }
        $decodedItems = json_decode($data->items);
        $decodedServices = $decodedItems->services;
        foreach ($decodedServices as $ser) {
            $service = Services::find($ser->service_id);
            if ($service) {
                $ser->cate_id = $service->cate_id;
                $categoryInfo = Category::find($service->cate_id);
                if ($categoryInfo) {
                    $ser->web_cates_data = $categoryInfo;
                }
            }
        }
        $data->items = json_encode($decodedItems);
        // $newItems = [];
        // foreach($decodedItems as $items)
        // {
        //     foreach($items as $item) {
        //         $salonService = SalonService::find($item->id);
        //         if($salonService)
        //         {
        //             $service = Services::find($salonService->service_id);
        //             if($service)
        //             {
        //                 if($service && $service->cate_id && $service->cate_id !=null){
        //                     $cats = Category::where('id',$service->cate_id)->first();
        //                     $salonService->web_cates_data = $cats;
        //                     $salonService->cate_id = $service->cate_id;
        //                     $salonService->name = $service->name;
        //                 }
        //             }
        //             $newItems[] = $salonService;
        //         }
        //     }
        // }
        // $newArray['services'] = $newItems;
        // $newArray['packages'] = [];
        // $data->items = json_encode($newArray);
        $slot = Timeslots::find($data->slot);
        if ($slot) {
            $slotTimes = json_decode($slot->slots);
            $data->slot = $slotTimes[0]->start_time . " - " . $slotTimes[0]->end_time;
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'month' => 'required',
            'year' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        if ($request->type == 'individual') {
            $monthData = Appointments::select(
                \DB::raw("COUNT(*) as count"),
                \DB::raw("DATE(save_date) as day_name"),
                \DB::raw("DATE(save_date) as day"),
                \DB::raw('ROUND(SUM(grand_total),2) AS total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 1 THEN grand_total ELSE 0 END), 2) AS COD_total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 5 THEN grand_total ELSE 0 END), 2) AS Online_total')
            )
                ->whereMonth('save_date', $request->month)
                ->whereYear('save_date', $request->year)
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('freelancer_id', $request->id)
                ->where('status', 4)
                ->get();
        }

        if ($request->type == 'salon') {
            $monthData = Appointments::select(
                \DB::raw("COUNT(*) as count"),
                \DB::raw("DATE(save_date) as day_name"),
                \DB::raw("DATE(save_date) as day"),
                \DB::raw('ROUND(SUM(grand_total),2) AS total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 1 THEN grand_total ELSE 0 END), 2) AS COD_total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 5 THEN grand_total ELSE 0 END), 2) AS Online_total')
            )
                ->whereMonth('save_date', $request->month)
                ->whereYear('save_date', $request->year)
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('salon_id', $request->id)
                ->where('status', 4)
                ->get();
        }
        $monthResponse = [];
        foreach ($monthData as $row) {
            $monthResponse['label'][] = date('l, d', strtotime($row->day_name));
            $monthResponse['data'][] = (int) $row->count;
            $monthResponse['total'][] = $row->total;
            $monthResponse['COD_total'][] = $row->COD_total;
            $monthResponse['Online_total'][] = $row->Online_total;
        }
        if (isset($monthData) && count($monthData) > 0) {
            $response = [
                'data' => $monthData,
                'chart' => $monthResponse,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'data' => [],
                'chart' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function getMonthsStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'year' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        if ($request->type == 'individual') {
            $monthData = Appointments::select(
                \DB::raw("COUNT(*) as count"),
                \DB::raw("MONTH(save_date) as day_name"),
                \DB::raw("MONTH(save_date) as day"),
                \DB::raw('ROUND(SUM(grand_total), 2) AS total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 1 THEN grand_total ELSE 0 END), 2) AS COD_total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 5 THEN grand_total ELSE 0 END), 2) AS Online_total')
            )
                ->whereYear('save_date', $request->year)
                ->where('freelancer_id', $request->id)
                ->where('status', 4)
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->get();
        }

        if ($request->type == 'salon') {
            $monthData = Appointments::select(
                \DB::raw("COUNT(*) as count"),
                \DB::raw("MONTH(save_date) as day_name"),
                \DB::raw("MONTH(save_date) as day"),
                \DB::raw('ROUND(SUM(grand_total), 2) AS total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 1 THEN grand_total ELSE 0 END), 2) AS COD_total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 5 THEN grand_total ELSE 0 END), 2) AS Online_total')
            )
                ->whereYear('save_date', $request->year)
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('salon_id', $request->id)
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
        foreach ($monthData as $row) {
            $monthResponse['label'][] = date('F', mktime(0, 0, 0, $row->day_name, 10));
            $monthResponse['data'][] = (int) $row->count;
            $monthResponse['total'][] = $row->total;
        }
        if (isset($monthData) && count($monthData) > 0) {
            $response = [
                'data' => $monthData,
                'chart' => $monthResponse,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'data' => [],
                'chart' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function getAllStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }

        if ($request->type == 'individual') {
            $monthData = Appointments::select(
                \DB::raw("COUNT(*) as count"),
                \DB::raw("YEAR(save_date) as day_name"),
                \DB::raw("YEAR(save_date) as day"),
                \DB::raw('ROUND(SUM(grand_total), 2) AS total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 1 THEN grand_total ELSE 0 END), 2) AS COD_total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 5 THEN grand_total ELSE 0 END), 2) AS Online_total')
            )
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('freelancer_id', $request->id)
                ->where('status', 4)
                ->get();
        }

        if ($request->type == 'salon') {
            $monthData = Appointments::select(
                \DB::raw("COUNT(*) as count"),
                \DB::raw("YEAR(save_date) as day_name"),
                \DB::raw("YEAR(save_date) as day"),
                \DB::raw('ROUND(SUM(grand_total), 2) AS total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 1 THEN grand_total ELSE 0 END), 2) AS COD_total'),
                \DB::raw('ROUND(SUM(CASE WHEN pay_method = 5 THEN grand_total ELSE 0 END), 2) AS Online_total')
            )
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('salon_id', $request->id)
                ->where('status', 4)
                ->get();
        }
        $monthData = $monthData->map(function ($item) {
            $item->total = number_format((float)$item->total, 2, '.', '');
            $item->COD_total = number_format((float)$item->COD_total, 2, '.', '');
            $item->Online_total = number_format((float)$item->Online_total, 2, '.', '');
            return $item;
        });
        $monthResponse = [];
        foreach ($monthData as $row) {
            $monthResponse['label'][] = date('Y', strtotime($row->day_name));
            $monthResponse['data'][] = (int) $row->count;
            $monthResponse['total'][] = $row->total;
            $monthResponse['COD_total'][] = $row->COD_total;
            $monthResponse['Online_total'][] = $row->Online_total;
        }
        if (isset($monthData) && count($monthData) > 0) {
            $response = [
                'data' => $monthData,
                'chart' => $monthResponse,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'data' => [],
                'chart' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function calendarView(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        if ($request->type == 'individual') {
            $monthData = Appointments::select(\DB::raw("COUNT(*) as count"), \DB::raw("DATE(save_date) as day_name"), \DB::raw("DATE(save_date) as day"), \DB::raw('SUM(total) AS total'))
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('freelancer_id', $request->id)
                ->get();
        }

        if ($request->type == 'salon') {
            $monthData = Appointments::select(\DB::raw("COUNT(*) as count"), \DB::raw("DATE(save_date) as day_name"), \DB::raw("DATE(save_date) as day"), \DB::raw('SUM(total) AS total'))
                ->groupBy('day_name', 'day')
                ->orderBy('day')
                ->where('salon_id', $request->id)
                ->get();
        }
        if (isset($monthData) && count($monthData) > 0) {
            $response = [
                'data' => $monthData,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'data' => [],
                'success' => false,
                'status' => 200
            ];
            return response()->json($response, 200);
        }
    }

    public function getByDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'date' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        if ($request->type == 'individual') {
            $data = Appointments::where('freelancer_id', $request->id)->whereDate('save_date', $request->date)->orderBy('id', 'desc')->get();
            foreach ($data as $loop) {
                $loop->individualInfo = DB::table('individual')
                    ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                    ->join('users', 'individual.uid', 'users.id')
                    ->where('individual.uid', $loop->freelancer_id)
                    ->first();
                $loop->userInfo = User::select('id', 'first_name', 'last_name', 'cover')->where('id', $loop->uid)->first();
                $decodedItems = json_decode($loop->items);
                $decodedServices = $decodedItems->services;
                foreach ($decodedServices as $ser) {
                    $service = Services::find($ser->service_id);
                    if ($service) {
                        $ser->cate_id = $service->cate_id;
                        $categoryInfo = Category::find($service->cate_id);
                        if ($categoryInfo) {
                            $ser->web_cates_data = $categoryInfo;
                        }
                    }
                }
                $loop->items = json_encode($decodedItems);
                // $decodedItems = json_decode($loop->items);
                // $newItems = [];
                // foreach($decodedItems as $item)
                // {
                //     $salonService = SalonService::find($item);
                //     if($salonService)
                //     {
                //         $service = Services::find($salonService->service_id);
                //         if($service)
                //         {
                //             if($service && $service->cate_id && $service->cate_id !=null){
                //                 $cats = Category::where('id',$service->cate_id)->first();
                //                 $salonService->web_cates_data = $cats;
                //                 $salonService->cate_id = $service->cate_id;
                //                 $salonService->name = $service->name;
                //             }
                //         }
                //         $newItems[] = $salonService;
                //     }
                // }
                // $newArray['services'] = $newItems;
                // $newArray['packages'] = [];
                // $loop->items = json_encode($newArray);
            }
            $response = [
                'data' => $data,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }

        if ($request->type == 'salon') {
            $data = Appointments::where('salon_id', $request->id)->whereDate('save_date', $request->date)->orderBy('id', 'desc')->get();
            foreach ($data as $loop) {
                $loop->salonInfo = Salon::where('uid', $loop->salon_id)->first();
                $loop->userInfo = User::select('id', 'first_name', 'last_name', 'cover')->where('id', $loop->uid)->first();
                $decodedItems = json_decode($loop->items);
                $decodedServices = $decodedItems->services;
                foreach ($decodedServices as $ser) {
                    $service = Services::find($ser->service_id);
                    if ($service) {
                        $ser->cate_id = $service->cate_id;
                        $categoryInfo = Category::find($service->cate_id);
                        if ($categoryInfo) {
                            $ser->web_cates_data = $categoryInfo;
                        }
                    }
                }
                $decodedPackages = $decodedItems->packages;
                foreach ($decodedPackages as $pack) {
                    foreach ($pack->services as $packSer) {
                        $service = SalonService::find($packSer->id);
                        if ($service) {
                            $packSer->gender = $service->gender;
                        }
                    }
                }
                $loop->items = json_encode($decodedItems);
                // $decodedItems = json_decode($loop->items);
                // $newItems = [];
                // foreach($decodedItems as $item)
                // {
                //     $salonService = SalonService::find($item);
                //     if($salonService)
                //     {
                //         $service = Services::find($salonService->service_id);
                //         if($service)
                //         {
                //             if($service && $service->cate_id && $service->cate_id !=null){
                //                 $cats = Category::where('id',$service->cate_id)->first();
                //                 $salonService->web_cates_data = $cats;
                //                 $salonService->cate_id = $service->cate_id;
                //                 $salonService->name = $service->name;
                //             }
                //         }
                //         $newItems[] = $salonService;
                //     }
                // }
                // $newArray['services'] = $newItems;
                // $newArray['packages'] = [];
                // $loop->items = json_encode($newArray);
            }
            $response = [
                'data' => $data,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }
    }

    public function printInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'token'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        try {
            $papabearGST = '32DCOPA4075A1Z4';
            $papabearPAN = 'DCOPA4075A';
            $papabearAddress = 'Papa Bear, Unique Beauty Solution, Njanickal Building, Brahmapuram P.O, Ernakulam, Kerala-India, 682303';
            $data = Appointments::find($request->id);
            $customer = User::find($data->uid);
            $customerName = $customer->first_name . ' ' . $customer->last_name;
            $customerAddress = $data->appointments_to == 1 ? json_decode($data->address) : '';
            $isShipping = $data->appointments_to == 1 ? true : false;
            $papabearInvoice = Invoice::where('appointment_id', $data->id)->where('type', 'Host')->first();
            $partnerInvoice = Invoice::where('appointment_id', $data->id)->where('type', 'Partner')->first();
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
                $papabearInvoice->appointment_id = $data->id;
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
                $partnerInvoice->appointment_id = $data->id;
                $partnerInvoice->partner_id = $partnerInformation->uid;
                $partnerInvoice->type = 'Partner';
                $partnerInvoice->fiscal_year = $fiscalYear;
                $partnerInvoice->save();
            }
            $items = json_decode($data->items, true);

            $items['grandTotal'] = (float) $data->grand_total;
            $items['discount'] = (float) $data->discount ?? 0;
            $items['wallet'] = (float) $data->wallet_price ?? 0;
            $items['total_in_words'] = $this->convertNumberToWords($data->grand_total);

            // Distance charge (show tax, don’t add to grandTotal)
            $distanceCharge = isset($data->distance_cost) ? (float) $data->distance_cost : 0;
            if ($distanceCharge > 0) {
                $distanceTax = $distanceCharge * 0.18;
                $items['distance_cost'] = (float) $distanceCharge;
                $items['distance_cost_tax'] = (float) $distanceTax;
            } else {
                $items['distance_cost'] = null;
                $items['distance_cost_tax'] = null;
            }
            $html = view('papabear-invoice', compact(
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
                'items',
                'isShipping'
            ))->render();
            $pdf = Pdf::loadView('papabear-invoice', compact(
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
                'items',
                'isShipping'
            ))->setPaper('a4', 'portrait')->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
            $sanitizedFileName = str_replace(['/', '\\'], '-', $sellerInvoiceNumber) . '.pdf';
            if ($request->query('type') == 'download') {
                return $pdf->download($sanitizedFileName . '.pdf');
            }
            if ($request->query('type', 'display') == 'display') {
                return view('papabear-invoice-display', [
                    'pdf' => $html,
                    'invoiceNumber' => $sanitizedFileName
                ]);
            }
            return $pdf->stream($sanitizedFileName . '.pdf');
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);
        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);
        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 401);
        }
    }

    public function printCommissionInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'token'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        try {
            $papabearGST = '32DCOPA4075A1Z4';
            $papabearPAN = 'DCOPA4075A';
            $papabearAddress = 'Papa Bear, Unique Beauty Solution, Njanickal Building, Brahmapuram P.O, Ernakulam, Kerala-India, 682303';
            $data = Appointments::find($request->id);
            $customer = User::find($data->uid);
            $customerName = $customer->first_name . ' ' . $customer->last_name;
            $customerAddress = $data->appointments_to == 1 ? json_decode($data->address) : '';
            $papabearInvoice = Invoice::where('appointment_id', $data->id)->where('type', 'Host')->first();
            $partnerInvoice = Invoice::where('appointment_id', $data->id)->where('type', 'Partner')->first();
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
                $papabearInvoice->appointment_id = $data->id;
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
                $partnerInvoice->appointment_id = $data->id;
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
                return $pdf->download($sanitizedFileName . '.pdf');
            }
            if ($request->query('type', 'display') == 'display') {
                return view('papabear-invoice-display', [
                    'pdf' => $html,
                    'invoiceNumber' => $sanitizedFileName
                ]);
            }
            return $pdf->stream($sanitizedFileName . '.pdf');
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);
        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);
        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 401);
        }
    }

    public function orderInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'token'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        try {
            // $data = DB::table('appointments')
            // ->select('appointments.*','users.first_name as user_first_name','freelancer.first_name as freelancer_fname','freelancer.last_name as freelancer_lname',
            // 'freelancer.email as freelancer_email','freelancer.mobile as freelancer_mobile','users.last_name as user_last_name','users.cover as user_cover','users.fcm_token as user_fcm_token','users.mobile as user_mobile','users.email as user_email')
            // ->join('users', 'appointments.uid', '=', 'users.id')
            // ->join('users as freelancer', 'appointments.freelancer_id', '=', 'freelancer.id')
            // ->where('appointments.id',$request->id)
            // ->first();
            // $general = Settings::first();
            // $addres ='';
            // $addres = json_decode($data->address);

            $data = Appointments::find($request->id);
            $data->userInfo = User::where('id', $data->uid)->first();
            if ($data->freelancer_id == 0) {
                $data->salonInfo = Salon::where('uid', $data->salon_id)->first();
                $data->ownerInfo = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->salon_id)->first();
            } else {
                $data->individualInfo = DB::table('individual')
                    ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
                    ->join('users', 'individual.uid', 'users.id')
                    ->where('individual.uid', $data->freelancer_id)
                    ->first();
                $data->ownerInfo = User::select('first_name', 'last_name', 'email', 'mobile', 'country_code', 'fcm_token', 'cover')->where('id', $data->freelancer_id)->first();
            }

            $general = Settings::first();
            $addres = '';
            if ($data->appointments_to == 1) {
                $addres = json_decode($data->address);
                // $addres = $compressed->house .' '.$compressed->landmark .' '.$compressed->address .' '.$compressed->pincode;
            }

            $data->items = json_decode($data->items);
            $general->social = json_decode($general->social);
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
            $data->pay_method = $paymentName[$data->pay_method];
            $response = [
                'data' => $data,
                'email' => $general->email,
                'general' => $general,
                'delivery' => $addres
            ];
            // echo json_encode($data);
            return view('appointment-invoice', $response);
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 401], 401);
        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 401], 401);
        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 401);
        }
    }

    public function getAppointmentsSalonStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'from'     => 'required',
            'to'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $from = date($request->from);
        $to = date($request->to);
        $data = Appointments::whereRaw('FIND_IN_SET("' . $request->id . '",salon_id)')->whereBetween('save_date', [$from, $to])->where('status', 4)->orderBy('id', 'desc')->get();
        $commission = DB::table('commission')->select('rate')->where('uid', $request->id)->first();
        $response = [
            'data' => $data,
            'commission' => $commission,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getAppointmentsFreelancersStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'from'     => 'required',
            'to'     => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $from = date($request->from);
        $to = date($request->to);
        $data = Appointments::whereRaw('FIND_IN_SET("' . $request->id . '",freelancer_id)')->whereBetween('save_date', [$from, $to])->where('status', 4)->orderBy('id', 'desc')->get();
        $commission = DB::table('commission')->select('rate')->where('uid', $request->id)->first();
        $response = [
            'data' => $data,
            'commission' => $commission,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'type' => 'required',
            'reason' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        if ($request->type == "0") {
            $datas = Appointments::where('freelancer_id', $request->uid)->where('save_date', $request->date)->whereIn('status', [0,1,3,7])->get();
        } else {
            $datas = Appointments::where('salon_id', $request->uid)->where('save_date', $request->date)->whereIn('status', [0,1,3,7])->get();
        }
        $user = User::find($request->uid);
        $generalInfo = Settings::take(1)->first();
        foreach ($datas as $data1) {
            $data1->update(['status' => 5, 'notes' => $request->reason]);
            $freelancer = Salon::where('uid', $data1->salon_id)->first();
            if (is_null($freelancer)) {
                $freelancer = User::find($data1->freelancer_id);
            }
            $statusText = "cancelled";
            $customer = User::find($data1->uid);
            $mail = $customer->email;
            $subject = "Appointment Cancelled";
            $username = $customer->first_name . ' ' . $customer->last_name;
            Mail::send(
                'mails/details',
                [
                    'app_name'      => $generalInfo->name,
                    'orderType' => 'Appointment',
                    'date' => $data1->save_date,
                    'order_id' => $data1->id,
                    'business_name' => $data1->freelancer_id == 0 ? $freelancer->name : $freelancer->first_name . ' ' . $freelancer->last_name,
                    'status' => $statusText . ' due to ' . $request->reason,
                    'messageType' => 'Customer'
                ],
                function ($message) use ($mail, $username, $subject, $generalInfo) {
                    $message->to($mail, $username)
                        ->subject($subject);
                    $message->from($generalInfo->email, $generalInfo->name);
                }
            );
            Notification::create([
                'uid' => $data1->uid,
                'title' => "Appointment Cancelled",
                'message' => "Your appointment with " . $user->name ? $user->name : $user->first_name . ' ' . $user->last_name .  " has been cancelled...",
                'type' => 'Appointment',
                'appointment_id' => $data1->id
            ]);
            Notification::create([
                'uid' => $request->uid,
                'title' => "Appointment Cancelled",
                'message' => "You cancelled your appointment due to " . $request->reason,
                'type' => 'Appointment',
                'appointment_id' => $data1->id
            ]);
            try {
                $user = User::find($data1->uid);
                if ($user && $user->fcm_token) {
                    $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                    $messaging = $firebase->createMessaging();
                    $message = CloudMessage::fromArray([
                        'token' => $user->fcm_token,
                        'notification' => [
                            'title' => "Appointment Cancelled",
                            'body' => "Your appointment have been cancelled by partner..."
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
                $partner = User::find($data1->freelancer_id == 0 ? $data1->salon_id : $data1->freelancer_id);
                if ($partner && $partner->fcm_token) {
                    $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                    $messaging = $firebase->createMessaging();
                    $message = CloudMessage::fromArray([
                        'token' => $partner->fcm_token,
                        'notification' => [
                            'title' => "Appointment Cancelled",
                            'body' => "Your appointment have been cancelled by partner..."
                        ]
                    ]);
                    $messaging->send($message);
                }
            } catch (Exception $e) {
                $partner->fcm_token = null;
                $partner->save();
                Log::error($e->getMessage());
            }
        }
        if (is_null($datas)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        $response = [
            'data' => true,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getPreviousAppointements(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'salon_id' => 'required',
            'freelancer_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Appointments::where('salon_id', $request->salon_id)->where('freelancer_id', $request->freelancer_id)->where('uid', $request->uid)->select('id', 'uid', 'freelancer_id', 'salon_id', 'items', 'grand_total', 'save_date', 'status')->get();
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'No appointments found',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        foreach ($data as $loop) {
            // $itemIds = json_decode($loop->items, true);
            // $loop->items = Services::whereIn('id', $itemIds)->get();
            $decodedItems = json_decode($loop->items);
            $decodedServices = $decodedItems->services;
            foreach ($decodedServices as $ser) {
                $service = Services::find($ser->service_id);
                if ($service) {
                    $ser->cate_id = $service->cate_id;
                    $categoryInfo = Category::find($service->cate_id);
                    if ($categoryInfo) {
                        $ser->web_cates_data = $categoryInfo;
                    }
                }
            }
            $loop->items = json_encode($decodedItems);
            $completion = AppointmentCompletion::where('appointment_id', $loop->id)->first();
            if ($completion) {
                $employee = Specialist::find($completion->employee_id);
                if ($employee) {
                    $loop->employee = $employee;
                }
                $loop->remarks = $completion->remarks;
                $loop->reminder_date = $completion->reminder_date;
                $loop->reminder_description = $completion->reminder_description;
            }
            $userDetails = User::find($request->uid);
            $loop->customer_name = $userDetails ? $userDetails->first_name . ' ' . $userDetails->last_name : '';
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200
        ];
        return response()->json($response, 200);
    }

    // Stream appointment invoice as PDF
    public function streamInvoice($filename)
    {
        try {
            $path = public_path('invoices/' . $filename); // adjust as needed
            if (!file_exists($path)) {
                abort(404);
            }
        } catch (Exception $e) {
            Log::error('Error accessing file: ' . $e->getMessage());
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    private function convertNumberToWords($number)
    {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;

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
