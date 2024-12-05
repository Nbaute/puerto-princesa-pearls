<?php

namespace App\Livewire\Guest;

use App\Http\Resources\FeaturedProductResource;
use App\Livewire\AppComponent;
use App\Models\Item;
use App\Traits\FiltersTrait;
use App\Traits\MyCartTrait;
use Livewire\Attributes\On;

class HomeLivewire extends AppComponent
{
    use FiltersTrait, MyCartTrait;
    public $featuredProducts = [];

    public function mount()
    {
        $items = Item::featured()->get();
        $this->featuredProducts = $items;
        $this->syncFilters();
    }
    public function render()
    {
        return view('livewire.guest.home-livewire');
    }


    #[On('confirmed')]
    public function confirmed()
    {
        $this->clearUserCart();
        $this->proceedAddToCart();
        $this->dispatch('cartUpdated', 'cartUpdated');
    }
}
