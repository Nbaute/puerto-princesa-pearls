<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\Otp;
use App\Models\OtpRateLimit;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;
use Throwable;

class OtpController extends Controller
{
    public function sendOtp(SendOtpRequest $request)
    {
        try {
            DB::beginTransaction();
            $otpMinuteExpiry = (int) setting('otpMinuteExpiry', 3);
            $ipTotalCount = OtpRateLimit::query()->where('ip_address', $request->ip())->where('created_at', 'like', date('Y-m-d %'))->count();

            $code = rand(111111, 999999);
            $otp = Otp::create(
                [
                    "phone" => $request->phone,
                    "code" => $code,
                    "purpose" => $request->purpose,
                ],
            );
            $otpRateLimitExpiresAt = Carbon::now()->addMinutes($otpMinuteExpiry);
            if ($ipTotalCount == 2) {
                $otpRateLimitExpiresAt->addMinutes(60);
            }

            $otpRateLimit = new OtpRateLimit();
            $otpRateLimit->ip_address = $request->ip();
            $otpRateLimit->phone_number = $request->phone;
            $otpRateLimit->expires_at = $otpRateLimitExpiresAt->format("Y-m-d H:i:s");
            $otpRateLimit->save();


            DB::commit();
            return $this->jsonSuccess("OTP sent! <Code: {$otp->code}>"); // TODO: hide the OTP code
        } catch (Throwable $ex) {
            DB::rollBack();
            return $this->jsonError("OTP failed to send. " . $ex->getMessage());
        }

    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            DB::beginTransaction();
            $otp = Otp::where(
                [
                    "phone" => $request->phone,
                    "code" => $request->code,
                    "purpose" => $request->purpose,
                ],
            )->whereNull('verified_at')->orderByDesc("created_at")->first();


            //invlaid
            if (empty($otp)) {
                return $this->jsonError("OTP is invalid!");
            }
            $otpMinuteExpiry = setting('otpMinuteExpiry', 3);
            $carbonExpiresAt = Carbon::parse($otp->updated_at)->addMinutes($otpMinuteExpiry);
            if (Carbon::now()->greaterThanOrEqualTo($carbonExpiresAt)) {
                return $this->jsonError("OTP is expired!");
            }
            $otp->verified_at = now();
            $token = Str::random();
            $otp->token = $token;
            $otp->save();
            DB::commit();
            return $this->jsonSuccess("OTP Verification is successful!", [
                'token' => $token,
            ]);
        } catch (Throwable $t) {
            return $this->jsonError("OTP verification error! " . $t->getMessage());
        }
    }
}
