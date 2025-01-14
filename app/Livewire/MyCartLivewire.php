<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentStatus;
use App\Models\OrderShippingStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class MyCartLivewire extends AppComponent
{
    public $user;
    public $quantities = [];
    public $cartItems = [];
    public $subtotal = 0;
    public $shippingFee = 0;
    public $shippingOptions = [['text' => 'Courier', 'shippingFee' => 100]];
    public $paymentMethods = ['GCash'];
    public $doCheckout = false;

    public $name;
    public $address;
    public $email;
    public $phone;
    public $selectedPaymentMethod;
    public $selectedShippingOption;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->subtotal = 0;
        $this->cartItems  = CartItem::query()->where(['user_id' => Auth::id(), 'cart_type' => 'cart'])->get();
        foreach ($this->cartItems as $cartItem) {
            $this->quantities['cart_item_' . $cartItem->id] = $cartItem->quantity;
            $this->subtotal += $cartItem->quantity * $cartItem->item->price;
        }
    }
    public function render()
    {
        return view('livewire.my-cart-livewire');
    }

    public function decrementQuantity($cartItemId)
    {
        $this->quantities['cart_item_' . $cartItemId] -= 1;
        if ($this->quantities['cart_item_' . $cartItemId] <= 0) {
            CartItem::query()->where('id', $cartItemId)->delete();
            $this->subtotal = 0;
            foreach ($this->cartItems as $k => $cartItem) {
                if ($cartItem->id == $cartItemId) {
                    unset($this->cartItems[$k]);
                } else {
                    $this->subtotal += $cartItem->quantity * $cartItem->item->price;
                }
            }
        } else {
            $this->subtotal = 0;
            CartItem::query()->where('id', $cartItemId)->update(['quantity' =>  $this->quantities['cart_item_' . $cartItemId]]);
            foreach ($this->cartItems as $k => $cartItem) {
                if ($cartItem->id == $cartItemId) {
                    $this->cartItems[$k] = $cartItem->refresh();
                }
                $this->subtotal += $cartItem->quantity * $cartItem->item->price;
            }
        }

        $this->dispatch('cartUpdated', 'cartUpdated');
    }
    public function incrementQuantity($cartItemId)
    {
        $this->quantities['cart_item_' . $cartItemId] += 1;
        CartItem::query()->where('id', $cartItemId)->update(['quantity' =>  $this->quantities['cart_item_' . $cartItemId]]);
        $this->subtotal = 0;
        foreach ($this->cartItems as $k => $cartItem) {
            if ($cartItem->id == $cartItemId) {
                $this->cartItems[$k] = $cartItem->refresh();
            }
            $this->subtotal += $cartItem->quantity * $cartItem->item->price;
        }
        $this->dispatch('cartUpdated', 'cartUpdated');
    }

    public function updateQuantity($cartItemId)
    {
        $quantity = $this->quantities['cart_item_' . $cartItemId];
        if ($quantity <= 0) {
            CartItem::query()->where('id', $cartItemId)->delete();
            $this->subtotal = 0;
            foreach ($this->cartItems as $k => $cartItem) {
                if ($cartItem->id == $cartItemId) {
                    unset($this->cartItems[$k]);
                } else {
                    $this->subtotal += $cartItem->quantity * $cartItem->item->price;
                }
            }
        } else {
            CartItem::query()->where('id', $cartItemId)->update(['quantity' =>  $this->quantities['cart_item_' . $cartItemId]]);
            $this->subtotal = 0;
            foreach ($this->cartItems as $k => $cartItem) {
                if ($cartItem->id == $cartItemId) {
                    $this->cartItems[$k] = $cartItem->refresh();
                }
                $this->subtotal += $cartItem->quantity * $cartItem->item->price;
            }
        }
        $this->dispatch('cartUpdated', 'cartUpdated');
    }

    public function checkoutCart()
    {
        $this->doCheckout = true;
    }

    public function cancelCheckout()
    {
        $this->doCheckout = false;
        $this->shippingFee = 0;
    }

    public function setShippingOption($text)
    {
        foreach ($this->shippingOptions as $k => $shippingOption) {
            if ($shippingOption['text'] == $text) {
                $this->shippingFee = $shippingOption['shippingFee'];
                $this->selectedShippingOption = $shippingOption;
                break;
            }
        }
    }

    public function setPaymentMethod($text)
    {
        $this->selectedPaymentMethod = $text;
    }

    public function placeOrder()
    {
        $data = [];
        try {
            $data = $this->validate(
                [
                    'user.id' => 'required|exists:users,id',
                    'cartItems.*.item_id' => 'required|exists:items,id',
                    'selectedShippingOption.text' => 'required|in:Courier',
                    'shippingFee' => 'required|numeric',
                    'address' => 'required',
                    'name' => 'required',
                    'phone' => 'sometimes',
                    'email' => 'sometimes|email',
                    'selectedPaymentMethod' => 'required|in:GCash,Maya',
                    'subtotal' => 'required|numeric',

                ]
            );

            try {
                DB::beginTransaction();
                $order = new Order();
                do {
                    $order->transaction_code =  strtoupper(Str::random(10)) . $order->id;
                } while (Order::where('transaction_code', $order->transaction_code)->exists());
                $order->user_id = $this->user->id;
                $order->shop_id = $this->cartItems[0]->item->shop->id;
                $order->shipping_method = $this->selectedShippingOption['text'];
                $order->shipping_fee = $this->selectedShippingOption['shippingFee'];
                $order->shipping_address = $this->address;
                $order->shipping_status = "unpaid";
                $order->billing_customer_name = $this->name ?? $this->user->name;
                $order->billing_customer_phone = $this->phone ?? $this->user->phone;
                $order->billing_customer_email = $this->email ?? $this->user->email;
                $order->payment_method = $this->selectedPaymentMethod;
                $order->payment_status = 'pending';
                $order->sub_total = $this->subtotal;
                $order->total = $this->subtotal + $order->shipping_fee;
                $order->save();

                foreach ($this->cartItems as $cartItem) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->item_id = $cartItem->item_id;
                    $orderItem->quantity = $cartItem->quantity;
                    $orderItem->price = $cartItem->item->price;
                    $orderItem->amount = $cartItem->item->price * $cartItem->quantity;
                    $orderItem->save();
                }

                $orderPaymentStatus = new OrderPaymentStatus();
                $orderPaymentStatus->order_id = $order->id;
                $orderPaymentStatus->name = "pending";
                $orderPaymentStatus->save();

                $orderShippingStatus = new OrderShippingStatus();
                $orderShippingStatus->order_id = $order->id;
                $orderShippingStatus->name = "unpaid";
                $orderShippingStatus->save();


                DB::commit();
                return $this->redirect("/orders/{$order->transaction_code}");
            } catch (Throwable $t) {
                DB::rollBack();
                $this->errorAlert("An error occurred! {$t->getMessage()}");
            }
        } catch (Throwable $t) {
            $this->errorAlert($t->getMessage());
        }
    }
}
