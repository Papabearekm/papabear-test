<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\Individual;
use App\Models\Notification;
use App\Models\ProductOrders;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index(Request $request) {
        $validator = Validator::make($request->all(),[
            'uid' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => "Validation Error " . $validator->errors(),
                'status'=> 500
            ],400);
        }
        $data = Notification::where('uid',$request->uid)->get();
        if(count($data) == 0)
        {
            return response()->json([
                'success' => false,
                'message' => 'No notifications',
                'status' => 404
            ],404);
        }
        foreach($data as $notification)
        {
            if($notification->appointment_id)
            {
                if($notification->type == "Appointment")
                {
                    $appointment = Appointments::find($notification->appointment_id);
                    $user = Salon::where('uid', $appointment->salon_id)->first();
                    if(is_null($user)) {
                        $user = User::find($appointment->freelancer_id);
                    }
                    $customer = User::find($appointment->uid);
                    $temp = array(
                        'customer' => $customer->first_name . ' ' . $customer->last_name,
                        'appointment_id' => $appointment->id,
                        'business_name' => $appointment->freelancer_id == 0 ? $user->name : $user->first_name . ' ' . $user->last_name,
                        'price' => $appointment->grand_total,
                        'date' => $appointment->save_date,
                        'status' => $appointment->status
                    );
                    $notification->data = $temp;
                } else if($notification->type == "Product") {
                    $productOrder = ProductOrders::find($notification->appointment_id);
                    $customer = User::find($productOrder->uid);
                    $user = Salon::where('uid', $productOrder->salon_id)->first();
                    if(is_null($user)) {
                        $user = User::find( $productOrder->freelancer_id);
                    }
                    $temp = array(
                        'order_id' => $productOrder->id,
                        'customer' => $customer->first_name . ' ' . $customer->last_name,
                        'business_name' => $productOrder->freelancer_id == 0 ? $user->name : $user->first_name . ' ' . $user->last_name,
                        'price' => $productOrder->grand_total,
                        'date' => $productOrder->date_time,
                        'status' => $productOrder->status
                    );
                    $notification->data = $temp;
                }
            } else {
                $notification->data = null;
            }
        }
        return response()->json([
            'success' => true,
            'data' => $data,
            'status' => 200
        ]);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(),[
            'id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Validation Error " . $validator->errors(),
                'status'=> 500
            ],400);
        }

        $data = Notification::find($request->id);
        if(is_null($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
                'status' => 404
            ],400);
        }

        $data->status = "Read";
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Marked as read',
            'status' => 200
        ],200);
    }
}
