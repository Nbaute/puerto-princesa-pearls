<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Shop;
use App\Traits\MyCartTrait;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProductLivewire extends Component
{
    use MyCartTrait, WithFileUploads;
    public $product;
    public $name;
    public $price;
    public $shop;
    public $categories = [];
    public $productPicture;
    public function mount()
    {
        $username = Route::current()->parameters()['username'];
        $this->shop = Shop::where('username', $username)->first();
        $this->categories = [];
    }


    public function render()
    {
        return view('livewire.create-product-livewire');
    }
    public function saveChanges()
    {
        $data = [];
        $data = $this->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:1',
            'productPicture' => 'image|max:9999'
        ]);
        $this->product = new Item();
        $this->product->shop_id = $this->shop->id;
        $this->product->name = $data['name'];
        $this->product->price = $data['price'];
        $this->product->save();
        if ($this->productPicture) {
            $this->product->clearMediaCollection('images');
            $this->product->addMedia($this->productPicture->getRealPath())->toMediaCollection('images');
            $this->product->refresh();
            $this->productPicture = null;
            $this->dispatch('productUpdated', $this->product);
        }
        $this->successAlert();

        return $this->redirect("/my/shops/{$this->shop->username}/products/{$this->product->id}", true);
    }
}
