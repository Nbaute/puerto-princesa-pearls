<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Shop;
use Livewire\Component;

class SearchShopsAndItemsLivewire extends Component
{
    public $q = '';
    public $products = [];
    public $shops = [];
    public function mount()
    {
        $this->q = $_GET['q'] ?? '';

        if (empty($this->q)) {
            $this->products = [];
            $this->shops = [];
        } else {


            $this->products = Item::query()
                ->where('status', 'active')
                ->where(fn($q) => $q->where('name', 'like', "%{$this->q}%")
                    ->orWhereHas('shop', fn($q) => $q->where('name', 'like', "%{$this->q}%")))
                ->limit(20)->get();
            $this->shops = Shop::query()
                ->where('status', 'active')
                ->where('name', 'like', "%{$this->q}%")
                ->limit(20)->get();
        }
    }
    public function render()
    {
        return view('livewire.search-shops-and-items-livewire');
    }
}