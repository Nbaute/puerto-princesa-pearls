<?php

namespace App\Livewire;

use Livewire\Component;

class OrdersLivewire extends Component
{
    public $shippingOptions = ['Courier'];
    public $paymentMethods = ['GCash'];
    public function render()
    {
        return view('livewire.orders-livewire');
    }
}
