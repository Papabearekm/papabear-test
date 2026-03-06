<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\CouponRedeem;
use Illuminate\Http\Request;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\Offers;
use App\Models\ProductOrders;
use App\Models\User;
use Validator;
use DB;

class OffersController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'name' => 'required',
            'short_descriptions' => 'required',
            'code' => 'required',
            'type' => 'required',
            'discount' => 'required',
            'upto' => 'required',
            'expire' => 'required',
            'max_usage' => 'required',
            'min_cart_value' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $user = User::find($request->uid);
        if($user){
            if(!in_array($user->type,['salon','individual']))
            {
                return response()->json(['success' => false, 'message' => 'User is not salon or freelancer']);
            }
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        $data = $request->all();
        $data['validations'] = 0;
        $data['for'] = 0;
        $data['status'] = 1;
        $data['freelancer_ids'] = $request->uid;
        unset($data['uid']);
        $data = Offers::create($data);
        if (is_null($data)) {
            $response = [
                'data'=>$data,
                'message' => 'error',
                'status' => 500,
            ];
            return response()->json($response, 200);
        }
        unset($data['freelancer_ids'],$data['for'],$data['user_limit_validation'],$data['extra_field']);
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getActive(){
        $data = Offers::where('status', 1)->get();
        foreach($data as $dat)
        {
            $usedCount = 0;
            $usedCount = CouponRedeem::where('coupon_id', $dat->id)->count() ?? 0;
            unset($dat['for'],$dat['user_limit_validation'],$dat['extra_field']);
            $dat['max_usage_exceeded'] = $usedCount >= $dat['max_usage'];
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
    
    public function getStores(Request $request){
        $data = Offers::where('status', 1)->where('freelancer_ids',$request->uid)->get();
        foreach($data as $dat)
        {
            unset($dat['freelancer_ids'],$dat['for'],$dat['user_limit_validation'],$dat['extra_field']);
        }

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

        $data = Offers::find($request->id);
        $ids = explode(',',$data->freelancer_ids);
        $salons = Salon::WhereIn('uid',$ids)->get();
        // $individual = Individual::WhereIn('uid',$ids)->get();
        $individual =  DB::table('individual')
                ->select('individual.*','users.first_name as first_name','users.last_name as last_name')
                ->join('users','individual.uid','users.id')
                ->WhereIn('uid',$ids)
                ->get();
        $realData = [];
        $realData2 = [];
        foreach($salons as $row) {
            array_push($realData, (object)[
                    'name' => $row->name,
                    'id' => $row->uid,
            ]);
        }

        foreach($individual as $row) {
            array_push($realData2, (object)[
                    'name' => $row->first_name .' '.$row->last_name,
                    'id' => $row->uid,
            ]);
        }
        $data['salons'] = $realData;
        $data['freelancers'] = $realData2;
        unset($data['freelancer_ids'],$data['for'],$data['user_limit_validation'],$data['extra_field']);
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

    public function getCouponByUserId(Request $request) {
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
        $data = Offers::where('status', 1)->where('freelancer_ids', $request->id)->get();
        foreach($data as $dat)
        {
            unset($dat['freelancer_ids'],$dat['for'],$dat['user_limit_validation'],$dat['extra_field']);
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function updateStatus(Request $request){
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
        $data = Offers::find($request->id)->update($request->all());

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

        if($request->uid)
        {
            return response()->json(['success' => false, 'message' => 'Cannot update UID', 'status' => 500]);
        }

        $data = Offers::find($request->id)->update($request->all());

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
        $data = Offers::find($request->id);
        if ($data) {
            $data->delete();
            $response = [
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

    public function getAll(){
        $data = Offers::all();
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
}
