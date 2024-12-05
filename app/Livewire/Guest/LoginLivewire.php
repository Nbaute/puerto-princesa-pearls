<?php

namespace App\Livewire\Guest;

use App\Livewire\AppComponent;
use App\Models\User;
use Egulias\EmailValidator\Result\ValidEmail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LoginLivewire extends AppComponent
{
    public $email = '';
    public $password = '';
    public $showPassword = false;
    public function render()
    {
        return view('livewire.guest.login-livewire');
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function attemptLogin()
    {
        $this->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);
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
            $this->addError('password', $t->getMessage());
        }
    }
}
