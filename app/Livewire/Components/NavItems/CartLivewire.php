<?php

namespace App\Livewire\Components\NavItems;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartLivewire extends Component
{

    protected $listeners = [
        'cartUpdated' => 'syncCart', // Listen for 'cartUpdated' and call 'updateCartQuantity'
    ];

    public $quantity = 0;
    public $subtotal = 0;
    public $cartItems = [];

    #[On('cartUpdated')]
    public function syncCart()
    {
        $query = CartItem::query()->where('user_id', Auth::id())->where('cart_type', 'cart');
        $this->cartItems = $query->get();
        $this->quantity = $query->count();
        $this->subtotal = $this->cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->item->price;
        });
    }
    public function cartUpdated()
    {
        $this->syncCart();
    }
    public function mount()
    {
        $this->syncCart();
    }
    public function render()
    {
        return view('livewire.components.nav-items.cart-livewire');
    }
}
