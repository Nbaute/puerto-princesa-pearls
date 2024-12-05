<?php

namespace App\Livewire;


class GuestHomeLivewire extends AppComponent
{
    public $featuredProducts = [];
    public function render()
    {
        return view('guest.home');
    }
}
