<?php

namespace App\Traits;

use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Throwable;

trait MyCartTrait
{
    use AlertTrait;

    public $cartItems = [];
    public $cart = [];
    public $selectedItem;
    public $selectedQuantity = 1;
    public $isMine = false;

    public function checkMine()
    {
        try {
            if (Auth::check()) {
                $uri = (Route::current()->uri());
                if (strpos($uri, "my/shops") !== false) {
                    $this->isMine = true;
                }
            }
        } catch (Throwable $t) {
        }
    }

    public function syncCartItems()
    {
        /**
         * @var Collection $cartItems
         */
        $this->cartItems = CartItem::query()->where('user_id', Auth::id())->where('cart_type', 'cart')->get();
        $this->cartItems->map(function ($cartItem) {
            $this->cart['item_' . $cartItem->item_id] = $cartItem;
            return $cartItem;
        });
    }

    public function addToCart($itemId, $quantity = 1)
    {
        if (!Auth::check()) {
            return $this->redirect('/login', navigate: true);
        }

        $item = Item::find($itemId);
        if (empty($item)) {
            return;
        }

        if ($item->shop->user_id == Auth::id()) {
            $this->errorAlert("You cannot buy from your own shop!");
            return;
        }

        $existingCartItem = CartItem::query()->where('user_id', Auth::id())->where('cart_type', 'cart')->first();

        if ($existingCartItem) {

            if ($existingCartItem->item->shop_id != $item->shop_id) {

                $this->selectedItem = $item;
                $this->selectedQuantity = $quantity;
                $this->confirm("This will remove the other items in your cart. Continue?", [
                    'onConfirmed' => 'confirmed'
                ]);


                // dd($existingCartItem->item->shop_id . '_' . $item->shop_id);
                return;
            }
        }

        $this->selectedItem = $item;
        $this->selectedQuantity = $quantity;
        $this->proceedAddToCart();
    }



    public function proceedAddToCart()
    {
        $item = $this->selectedItem;
        $quantity = $this->selectedQuantity ?? 1;
        $cartItem = CartItem::query()->firstOrCreate(
            [
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'cart_type' => 'cart'
            ]
        );
        $cartItem->quantity += $quantity;
        $cartItem->save();

        $this->cart['item_' . $item->id] = $cartItem;
        $this->cartItems  = CartItem::query()->where(['user_id' => Auth::id(), 'cart_type' => 'cart'])->get();
        $this->successAlert("Added to cart successfully");
        $this->selectedItem = null;
        $this->selectedQuantity = 1;
    }


    public function clearUserCart()
    {
        CartItem::where(['user_id' => Auth::id(), 'cart_type' => 'cart'])->delete();
    }
}