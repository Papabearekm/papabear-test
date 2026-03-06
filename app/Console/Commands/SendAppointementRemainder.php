<?php

namespace App\Console\Commands;

use App\Models\AppointmentCompletion;
use App\Models\Appointments;
use App\Models\Salon;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class SendAppointementRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-appointment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders to users and partners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        Appointments::where('save_date', '<', $currentDate)->where('status', '0')->update(['status' => '2']);
        $appointmentIds = AppointmentCompletion::where('reminder_date', $currentDate)->pluck('appointment_id')->toArray();
        $appointments = Appointments::whereIn('id', $appointmentIds)->get();

        foreach ($appointments as $appointment) {
            // Logic to send reminders
            Log::info("Sending reminder for appointment ID: {$appointment->id}");
            try {
                $partner = User::find($appointment->freelancer_id == 0 ? $appointment->salon_id : $appointment->freelancer_id);
                $generalInfo = Settings::take(1)->first();
                if($partner) {
                    $customer = User::find($appointment->uid);
                    $mail = $partner->email;
                    $username = $partner->first_name . ' ' . $partner->last_name;
                    $subject = "Follow up on Appointment " . $appointment->id;
                    Mail::send('mails/reminder',
                    [
                        'app_name'      =>$generalInfo->name,
                        'follow_up_date' => $currentDate,
                        'customer_name' => $customer ? ($customer->first_name . ' ' . $customer->last_name) : '-',
                        'messageType' => 'Partner'
                    ]
                    , function($message) use($mail,$username,$subject,$generalInfo){
                        $message->to($mail, $username)
                        ->subject($subject);
                        $message->from($generalInfo->email,$generalInfo->name);
                    });
                    try {
                        if($partner && $partner->fcm_token) {
                            $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                            $messaging = $firebase->createMessaging();
                            $message = CloudMessage::fromArray([
                                'token' => $partner->fcm_token,
                                'notification' => [
                                    'title' => "Appointment Reminder",
                                    'body' => "Follow up on your previous appointment scheduled is for " . $currentDate
                                ]
                            ]);
                            $messaging->send($message);
                        }
                    } catch (\Exception $e) {
                        $partner->fcm_token = null;
                        $partner->save();
                        Log::error("Failed to send FCM notification for appointment ID: {$appointment->id} to partner. Error: " . $e->getMessage());
                    }
                } else {
                    Log::error("Partner not found for appointment ID: {$appointment->id}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to send reminder for appointment ID: {$appointment->id} to partner. Error: " . $e->getMessage());
            }
            try {
                $partner = $appointment->freelancer_id == 0 ? Salon::where( 'uid', $appointment->salon_id )->first() : User::find($appointment->freelancer_id);
                $customer = User::find($appointment->uid);
                $generalInfo = Settings::take(1)->first();
                if($customer) {
                    $mail = $customer->email;
                    $username = $customer->first_name . ' ' . $customer->last_name;
                    $subject = "Follow up on Appointment " . $appointment->id;
                    Mail::send('mails/reminder',
                    [
                        'app_name'      =>$generalInfo->name,
                        'follow_up_date' => $currentDate,
                        'business_name' => $partner ? ($appointment->freelancer_id == 0 ? $partner->name : ($partner->first_name . ' ' . $partner->last_name)) : '-',
                        'messageType' => 'Customer'
                    ]
                    , function($message) use($mail,$username,$subject,$generalInfo){
                        $message->to($mail, $username)
                        ->subject($subject);
                        $message->from($generalInfo->email,$generalInfo->name);
                    });
                    try {
                        if($customer && $customer->fcm_token) {
                            $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                            $messaging = $firebase->createMessaging();
                            $message = CloudMessage::fromArray([
                                'token' => $customer->fcm_token,
                                'notification' => [
                                    'title' => "Appointment Reminder",
                                    'body' => "Follow up on your previous appointment scheduled is for " . $currentDate
                                ]
                            ]);
                            $messaging->send($message);
                        }
                    } catch (\Exception $e) {
                        $customer->fcm_token = null;
                        $customer->save();
                        Log::error("Failed to send FCM notification for appointment ID: {$appointment->id} to customer. Error: " . $e->getMessage());
                    }
                } else {
                    Log::error("Customer not found for appointment ID: {$appointment->id}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to send reminder for appointment ID: {$appointment->id} to customer. Error: " . $e->getMessage());
            }
        }
    }
}
