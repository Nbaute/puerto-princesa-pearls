<?php

namespace App\Livewire\Guest;

use App\Livewire\AppComponent;
use Illuminate\Support\Facades\Redirect;

class ContactUsLivewire extends AppComponent
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public function render()
    {
        return view('livewire.guest.contact-us-livewire');
    }

    public function sendMessage()
    {
        $recipient = env('MAIL_FROM_ADDRESS', 'puertoprincesapearls@gmail.com');
        $mailto = sprintf(
            'mailto:' . $recipient . '?subject=%s&body=%s',
            rawurlencode($this->subject),
            rawurlencode("Name: $this->name\nEmail: $this->email\nMessage: $this->message")
        );
        $this->dispatch('openLink', $mailto);
    }
}