<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Rating extends Component
{
    public $value = 5;
    public $maxStars = 5;
    public string $sizeClass = 'w-4';

    public function mount() {}

    public function render()
    {
        return view('livewire.components.rating');
    }
}
