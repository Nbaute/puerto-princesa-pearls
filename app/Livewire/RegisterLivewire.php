<?php

namespace App\Livewire;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Throwable;

class RegisterLivewire extends AppComponent
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $showPassword = false;
    public $captcha = '';

    // protected $rules = [
    //     'name' => 'string|required',
    //     'email' => 'string|required|email|unique:users,email',
    //     'password' => 'string|required|min:8'
    // ];

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function attemptLogin()
    {
        try {
            if (Auth::attempt(
                [
                    'email' => $this->email,
                    'password' => $this->password
                ]
            )) {
                $user = User::query()->where('email', $this->email)->first();
                if (empty($user)) {
                    throw new Exception('User not found!');
                }
                return $this->redirect('/', navigate: true);
            } else {
                throw new Exception('Incorrect email/password!');
            }
        } catch (Throwable $t) {

            session()->flash('error_message', $t->getMessage());
        }
    }

    public function createAccount()
    {

        $result = $this->validate([
            'name' => 'string|required',
            'email' => 'string|required|email|unique:users,email',
            'password' => 'string|required|min:8',
            'captcha' => 'required|captcha'
        ]);

        $user = User::query()->create($result);
        Auth::attempt([
            'email' => $result['email'],
            'password' => $result['password']
        ]);
        return $this->redirect("/", navigate: true);

        // dd(request()->all());
    }
    public function render()
    {
        return view('livewire.register-livewire');
    }
}
