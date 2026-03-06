<?php
namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    public function sendVerification($phone)
    {
        return $this->twilio->verify->v2->services(config('services.twilio.verify_sid'))
                    ->verifications
                    ->create($phone, "sms");
    }

    public function checkVerification($phone, $code) {
        return $this->twilio->verify->v2->services(config('services.twilio.verify_sid'))
                    ->verificationChecks
                    ->create([
                        'to' => $phone,
                        'code' => $code
                    ]);
    }
}