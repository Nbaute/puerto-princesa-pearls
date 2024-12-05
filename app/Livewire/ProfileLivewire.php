<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class ProfileLivewire extends AppComponent
{
    use WithFileUploads;
    public User $user;
    public $name;
    public $email;
    public $password;
    public $currentPassword;
    public $confirmPassword;
    public $showCurrentPassword = false;
    public $showPassword = false;
    public $showConfirmPassword = false;

    public $profilePicture;
    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }
    public function render()
    {
        return view('livewire.profile-livewire');
    }

    public function onProfilePictureUpload()
    {
        $this->validate([
            'profilePicture' => 'image|max:9999',
        ]);
        $this->user->clearMediaCollection('image');
        $this->user->addMedia($this->profilePicture->getRealPath())->toMediaCollection('image');
        $this->user->refresh();
        $this->profilePicture = null;
        $this->dispatch('userUpdated', $this->user);
        $this->successAlert();
    }

    public function toggleCurrentPassword()
    {
        $this->showCurrentPassword = !$this->showCurrentPassword;
    }
    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }
    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function onUpdateDetails()
    {
        $basicDetails = $this->validate([
            'name' => 'required',
        ]);
        $this->user->name = $basicDetails['name'];
        $this->user->save();
        $this->successAlert();
    }
    public function onChangePassword()
    {
        $credentials = $this->validate([
            'currentPassword' => 'required',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|min:8',
        ]);

        if (!Hash::check($credentials['currentPassword'], $this->user->password)) {
            $this->addError('currentPassword', 'Incorrect Password!');
            return;
        }
        if ($credentials['password'] != $credentials['confirmPassword']) {
            $this->addError('confirmPassword', 'Password does not match!');

            return;
        }
        $this->user->password = Hash::make($credentials['password']);
        $this->user->save();
        $this->successAlert();
    }
}
