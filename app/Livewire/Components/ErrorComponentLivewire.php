<?php

namespace App\Livewire\Components;

use Livewire\Component;

class ErrorComponentLivewire extends Component
{
    public $errorTitle;
    public $message;
    public function render()
    {
        return view('livewire.components.error-component-livewire');
    }
}
