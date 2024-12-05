<?php

namespace App\Http\Requests;

use App\Models\Otp;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required|min:8',
            'image' => 'required|file',
            'auth_provider' => 'required|exists:auth_providers,name,is_active,1',
            'verification_token' => 'required',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation logic

            $otp = Otp::where(
                [
                    "phone" => $this->phone,
                    "purpose" => "register",
                    "token" => $this->verification_token,
                ],
            )->whereNotNull('verified_at')->orderByDesc("created_at")->first();
            if (empty($otp)) {
                $validator->errors()->add('verification_token', 'OTP error!');
            } else {

                $otpMinuteExpiry = setting('otpRegistrationMinuteExpiry', 10);
                $carbonExpiresAt = Carbon::parse($otp->verified_at)->addMinutes($otpMinuteExpiry);
                if (Carbon::now()->greaterThanOrEqualTo($carbonExpiresAt)) {
                    $validator->errors()->add('verification_token', 'OTP is expired!');
                }
            }
        });
    }

}
