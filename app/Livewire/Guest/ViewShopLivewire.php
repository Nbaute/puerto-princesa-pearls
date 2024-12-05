<?php

namespace App\Livewire\Guest;

use App\Http\Resources\FeaturedProductResource;
use App\Http\Resources\ShopResource;
use App\Livewire\AppComponent;
use App\Models\ItemCategory;
use App\Models\Shop;
use App\Traits\FiltersTrait;
use App\Traits\MyCartTrait;
use App\Traits\AlertTrait;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class ViewShopLivewire extends AppComponent
{
    use FiltersTrait, MyCartTrait, WithFileUploads, AlertTrait;
    public $username;
    public $name;
    public $canEdit = false;
    public $link;
    public $shop;
    public $shopPicture;
    public $paymentInstructions;

    public $products = [];

    public function mount()
    {
        $this->checkMine();
        $this->canEdit = $this->isMine;
        $this->username = Route::current()->parameters()['username'];
        $shop =  Shop::where('username', $this->username)->first();
        if (!empty($shop)) {
            $this->shop =  $shop;
            $this->name = $this->shop->name;
            $this->link = $this->shop->link;
            $this->paymentInstructions = $this->shop->payment_instructions;
            $this->products =  $shop->items()->when(!$this->isMine, fn($q) => $q->where('status', 'active'))->get();
            $this->syncFilters();
        }
    }
    public function render()
    {
        return view('livewire.guest.view-shop-livewire');
    }

    public function onShopPictureUpload()
    {
        $data = [];
        if ($this->shopPicture) {

            $data = $this->validate([
                'shopPicture' => 'image|max:9999',
                'username' => 'required|unique:shops,username,' . $this->shop->id,
                'name' => 'required',
                'link' => 'required',
                'paymentInstructions' => 'required',
            ]);
        } else {
            $data = $this->validate([
                'username' => 'required|unique:shops,username,' . $this->shop->id,
                'name' => 'required',
                'link' => 'required',
                'paymentInstructions' => 'required',
            ]);
        }
        $didUpdateUsername = false;
        $data['username'] = '@' . str_replace('@', '', $data['username']);
        if ($this->shop->username != $data['username']) {
            $didUpdateUsername = true;
        }
        $this->shop->username = $data['username'];
        $this->shop->name = $data['name'];
        $this->shop->link = $data['link'] ?? $this->shop->link;
        $this->shop->payment_instructions = $data['paymentInstructions'] ?? $this->shop->payment_instructions;
        $this->shop->save();

        if ($this->shopPicture) {
            $this->shop->clearMediaCollection('logos');
            $this->shop->addMedia($this->shopPicture->getRealPath())->toMediaCollection('logos');
        }

        if ($didUpdateUsername) {

            $this->successAlert();
            return $this->redirect("/my/shops/" . $this->shop->username);
        }

        $this->shop->refresh();
        $this->shopPicture = null;
        $this->dispatch('shopUpdated', $this->shop);
        $this->successAlert();
    }



    #[On('confirmed')]
    public function confirmed()
    {
        $this->clearUserCart();
        $this->proceedAddToCart();
        $this->dispatch('cartUpdated', 'cartUpdated');
    }

    #[On('activeFilters')]
    public function syncActiveFilters($filters)
    {
        // $this->successAlert(json_encode($filters));
        $this->products =  $this->shop->items()
            ->when(!$this->isMine, fn($q) => $q->where('status', 'active'))
            ->when(count($filters ?? []) > 0, fn($q)=>$q->whereHas('categories', fn($q) => $q->whereIn('item_categories.id', $filters ?? [])))
            ->get(); 
    }

    public function disableShop()
    {
        $this->shop->status = 'inactive';
        $this->shop->save();
        $this->successAlert();
    }
    public function enableShop()
    {
        $this->shop->status = 'active';
        $this->shop->save();
        $this->successAlert();
    }
    public function deleteShop()
    {
        $this->shop->delete();
        $this->successAlert();
        $this->redirect("/my/shops");
    }
}