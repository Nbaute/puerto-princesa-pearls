<?php

namespace App\Observers;
use App\Models\Otp;
use App\Services\SmsService;

class OtpObserver
{
    public function created(Otp $otp)
    {
        // $this->sendOtpViaSms($otp);
    }

    private function sendOtpViaSms(Otp $otp)
    {
        $otpMinuteExpiry = (int) setting('otpMinuteExpiry', 3);
        $message = "Your One-Time PIN (OTP) is {$otp->code}. This code expires in {$otpMinuteExpiry} minutes.";

        $sms = new SmsService();
        $sms->sendMessage($message, [$otp->phone]);
    }
}
