<?php

namespace App\Livewire\Guest;

use App\Http\Resources\FeaturedProductResource;
use App\Http\Resources\ShopResource;
use App\Livewire\AppComponent;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\Shop;
use App\Traits\MyCartTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Throwable;

class ShopsLivewire extends AppComponent
{
    use MyCartTrait;
    public $title = "Shops";
    private $shopsPaginator;
    public $featuredProducts = [];
    public $shops = [];
    public $isMine = false;


    public function mount()
    {
        $this->checkMine();
        $this->shopsPaginator = Shop::query()
            ->where('status', 'active')
            ->when($this->isMine, function ($q) {
                return $q->where('user_id', Auth::id());
            })
            ->paginate(10);
        $this->shops =  $this->shopsPaginator->items();
        $items = Item::featured()
            ->when($this->isMine, function ($q) {
                return $q->whereHas('shop', fn($q) => $q->where('user_id', Auth::id()));
            })
            ->get();
        $this->featuredProducts = $items;
    }
    public function render()
    {
        return view('livewire.guest.shops-livewire')->layoutData(['title' => $this->title]);;
    }

    #[On('confirmed')]
    public function confirmed()
    {
        $this->clearUserCart();
        $this->proceedAddToCart();
        $this->dispatch('cartUpdated', 'cartUpdated');
    }
}