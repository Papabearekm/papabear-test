<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\AppointmentCompletion;
use App\Models\Appointments;
use App\Models\Notification;
use App\Models\Salon;
use App\Models\Settings;
use App\Models\Specialist;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class AppointmentCompletionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required',
            'employee_id' => 'nullable',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = $request->all();
        $appointment = Appointments::find($request->appointment_id);
        if(is_null($appointment))
        {
            $response = [
                'data'=>$appointment,
                'message' => 'Appointment not found',
                'status' => 500,
            ];
            return response()->json($response, 200);
        }
        // Update withdrawal_balance or cod_balance
        if ($appointment->status == 4) { // completed
            $grandTotal = (float) $appointment->grand_total;
            if ($appointment->pay_method == 5) { // Online order
                // Add to withdrawal_balance
                $user = User::find($appointment->freelancer_id == 0 ? $appointment->salon_id : $appointment->freelancer_id);
                if ($user) {
                    $user->withdrawal_balance = ($user->withdrawal_balance ?? 0) + $grandTotal;
                    $user->save();
                }
            } elseif ($appointment->pay_method == 1) { // COD
                // Add 5% of grand_total to cod_balance
                $user = User::find($appointment->freelancer_id == 0 ? $appointment->salon_id : $appointment->freelancer_id);
                if ($user) {
                    $user->withdrawal_balance = ($user->withdrawal_balance ?? 0) - round($grandTotal * 0.05, 2);
                    $user->cod_balance = ($user->cod_balance ?? 0) + round($grandTotal * 0.05, 2);
                    $user->save();
                }
            }
        }
        $freelancer = Salon::where('uid', $appointment->salon_id)->first();
        if(is_null($freelancer)) {
            $freelancer = User::find($appointment->freelancer_id);
        }
        $data = AppointmentCompletion::create($data);
        if (is_null($data)) {
            $response = [
                'data'=>$data,
                'message' => 'error',
                'status' => 500,
            ];
            return response()->json($response, 200);
        }
        try {
            $user = User::find($appointment->uid);
            if($user && $user->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => "Appointment Completed",
                        'body' => "Your appointment have been completed successfully..."
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
            if($partner && $partner->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $partner->fcm_token,
                    'notification' => [
                        'title' => "Appointment Completed",
                        'body' => "Your appointment have been completed successfully..."
                    ]
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $partner->fcm_token = null;
            $partner->save();
            Log::error($e->getMessage());
        }
        Notification::create([
            'uid' => $appointment->uid,
            'title' => "Appointment Completed",
            'message' => "Your appointment with " . $appointment->freelancer_id == 0 ? $freelancer->name : $freelancer->first_name . ' ' . $freelancer->last_name . " has been completed successfully...\nReminder Description: " . $request->reminder_description 
            . "\nReminder Date: " . $request->reminder_date . "\nRemarks: " . $request->remarks . "\n Done By: " . ($request->employee_id != 0 ? Specialist::find($request->employee_id)->first_name . ' ' . Specialist::find($request->employee_id)->last_name : " "),
            'type' => 'Appointment',
            'appointment_id' => $appointment->id
        ]);
        Notification::create([
            'uid' => $appointment->freelancer_id == 0 ? $appointment->salon_id : $appointment->freelancer_id,
            'title' => "Appointment Completed",
            'message' => "Your appointment have been completed successfully...",
            'type' => 'Appointment',
            'appointment_id' => $appointment->id
        ]);
        $generalInfo = Settings::take(1)->first();
        if($appointment->status == 1) {
            $statusText = "accepted";
        } else if($appointment->status == 2) {
            $statusText = "declined";
        } else if($appointment->status == 3) {
            $statusText = "ongoing";
        } else if($appointment->status == 4) {
            $statusText = "completed";
        } else if($appointment->status == 5) {
            $statusText = "cancelled by user";
        } else if($appointment->status == 6) {
            $statusText = "refunded";
        } else if($appointment->status == 7) {
            $statusText = "delayed";
        } else if($appointment->status == 8) {
            $statusText = "pending payment";
        }
        $user = User::find($appointment->uid);
        $mail = $user->email;
        $username = $user->first_name . ' ' . $user->last_name;
        $subject = "Appointment " . $statusText;
        Mail::send('mails/details',
         [
            'app_name'      =>$generalInfo->name,
            'orderType' => 'Appointment',
            'date' => $appointment->save_date,
            'order_id' => $appointment->id,
            'business_name' => $appointment->freelancer_id == 0 ? $freelancer->name : $freelancer->first_name . ' ' . $freelancer->last_name,
            'status' => $statusText,
            'messageType' => 'Customer'
         ]
         , function($message) use($mail,$username,$subject,$generalInfo){
            $message->to($mail, $username)
            ->subject($subject);
            $message->from($generalInfo->email,$generalInfo->name);
        });
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}
