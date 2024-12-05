<?php

namespace App\Http\Requests;

use App\Models\Otp;
use App\Models\OtpRateLimit;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendOtpRequest extends FormRequest
{
    use JsonResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "purpose" => ["required", 'in:login,register,forgot-password,delete-account'],
            "phone" => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->purpose == 'register') {
                $userExists = User::query()->where('phone', $this->phone)->exists();
                if ($userExists) {
                    $validator->errors()->add('purpose', 'User already exists!');
                    return;
                }
            } else {
                $userExists = User::query()->where('phone', $this->phone)->exists();
                if (!$userExists) {
                    $validator->errors()->add('purpose', 'User doesn\'t exist!');
                    return;
                }
            }
            $otp = Otp::query()->where('phone', $this->phone)->where('purpose', $this->purpose)->whereNull('verified_at')->orderBy('id', 'desc')->first();

            $otpMinuteExpiry = (int) setting('otpMinuteExpiry', 3);
            if (!empty($otp)) {

                $carbonExpiresAt = Carbon::parse($otp->updated_at)->addMinutes($otpMinuteExpiry);
                if (Carbon::now()->lessThan($carbonExpiresAt)) {
                    $diff_seconds = Carbon::now()->diffInSeconds($carbonExpiresAt);
                    $validator->errors()->add('otp', "An existing OTP code was already sent. Try again later after {$diff_seconds}s!");
                    return;
                } else {
                    // $latestOtpRateLimit = OtpRateLimit::query()->where('ip_address', $this->ip())->where('phone_number', $this->phone)->orderByDesc("created_at")->first();
                    // if (!empty($latestOtpRateLimit)) {
                    //     if (Carbon::now()->lessThan(Carbon::parse($latestOtpRateLimit->expires_at))) {
                    //         $diff_seconds = Carbon::now()->diffInSeconds(Carbon::parse($latestOtpRateLimit->expires_at));
                    //         $validator->errors()->add('otp', "Max OTP requests reached! Please try again later after {$diff_seconds}s.");
                    //         return;
                    //     }
                    // }
                }
            }

            // $ipTotalCount = OtpRateLimit::query()->where('ip_address', $this->ip())->where('created_at', 'like', date('Y-m-d %'))->count();
            // $phoneTotalCount = OtpRateLimit::query()->where('phone_number', $this->phone)->where('created_at', 'like', date('Y-m-d %'))->count();
            // if ($ipTotalCount > 0) {
            //     $otpDeviceMaxLimitPerDay = setting('otpDeviceMaxLimitPerDay', 9);
            //     if ($ipTotalCount >= $otpDeviceMaxLimitPerDay) {
            //         $validator->errors()->add('otp', "This device is temporarily restricted to request SMS OTP codes for 1 day due to multiple requests.");
            //         return;
            //     }
            // }
            // if ($phoneTotalCount > 0) {
            //     $otpPhoneNumberMaxLimitPerDay = setting('otpPhoneNumberMaxLimitPerDay', 3);
            //     if ($phoneTotalCount >= $otpPhoneNumberMaxLimitPerDay) {
            //         $validator->errors()->add('otp', "This phone number is temporarily restricted to request SMS OTP codes for 1 day due to multiple requests");
            //         return;
            //     }
            // }
        });
    }


}
