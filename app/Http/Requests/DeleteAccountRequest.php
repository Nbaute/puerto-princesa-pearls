<?php

namespace App\Http\Requests;

use App\Models\Otp;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteAccountRequest extends FormRequest
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
            'password' => 'required',
            'verification_token' => 'required',
            'reason' => 'required|string',
            'phone' => 'required|exists:users,phone,is_active,1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation logic

            $otp = Otp::where(
                [
                    "phone" => $this->phone,
                    "purpose" => "delete-account",
                    "token" => $this->verification_token,
                ],
            )->whereNotNull('verified_at')->orderByDesc("created_at")->first();
            if (empty($otp)) {
                $validator->errors()->add('verification_token', 'OTP error!');
                return;
            } else {

                $otpMinuteExpiry = setting('otpRegistrationMinuteExpiry', 10);
                $carbonExpiresAt = Carbon::parse($otp->verified_at)->addMinutes($otpMinuteExpiry);
                if (Carbon::now()->greaterThanOrEqualTo($carbonExpiresAt)) {
                    $validator->errors()->add('verification_token', 'OTP is expired!');
                    return;
                }
            }
            $attempt = Auth::attempt(['phone' => $this->phone, 'password' => $this->password]);
            if (!$attempt) {
                $validator->errors()->add('password', 'Password incorrect!');
            }
        });
    }
}
