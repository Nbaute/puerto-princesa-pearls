<?php

namespace App\Http\Requests;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserLoginRequest extends FormRequest
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
            'email' => 'required_without:phone|exists:users,email,is_active,1',
            'phone' => 'required_without:email|exists:users,phone,is_active,1',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|exists:roles,name',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation logic
            if ($this->email && str_ends_with($this->email, '@example.com')) {
                $validator->errors()->add('email', 'Emails from @example.com are not allowed.');
            }

            if ($this->phone && strlen($this->phone) < 10) {
                $validator->errors()->add('phone', 'Phone number must be at least 10 digits.');
            }

            if ($this->email) {
                $user = User::query()->where('email', $this->email)->first();
                if (empty($user)) {
                    $validator->errors()->add('email', 'Email does not exist!');
                    return;
                }
            }
            if ($this->phone) {
                $user = User::query()->where('phone', $this->phone)->first();
                if (empty($user)) {
                    $validator->errors()->add('phone', 'Phone does not exist!');
                    return;
                }
            }
        });
    }

    public function getAttempt($keys = null)
    {
        $data = $this->all();
        $attempt = [
            'password' => $data['password'],
        ];
        if (isset($data['email'])) {
            $attempt['email'] = $data['email'];
        }
        if (isset($data['phone'])) {
            $attempt['phone'] = $data['phone'];
        }
        return $attempt;
    }
}
