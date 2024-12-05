<?php

namespace App\Livewire;

use App\Models\Shop;
use App\Traits\AlertTrait;
use App\Traits\FiltersTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateShopLivewire extends Component
{
    use AlertTrait;

    use  WithFileUploads;
    public $username;
    public $name;
    public $shop;
    public $link;
    public $paymentInstructions;
    public $shopPicture = null;

    public $products = [];


    public function render()
    {
        return view('livewire.create-shop-livewire');
    }



    public function mount() {}

    public function onShopPictureUpload()
    {
        $data = [];
        $data = $this->validate([
            'shopPicture' => 'image|max:9999',
            'username' => 'required|unique:shops,username',
            'name' => 'required',
            'link' => 'required',
            'paymentInstructions' => 'required',
        ]);
        $data['username'] = '@' . str_replace('@', '', $data['username']);
        $this->shop = new Shop();
        $this->shop->username = $data['username'];
        $this->shop->user_id = Auth::id();
        $this->shop->name = $data['name'];
        $this->shop->status = 'active';
        $this->shop->payment_instructions = $data['paymentInstructions'];
        $this->shop->link = $data['link'];
        $this->shop->save();

        $this->shop->clearMediaCollection('logos');
        $this->shop->addMedia($this->shopPicture->getRealPath())->toMediaCollection('logos');

        $this->shop->refresh();
        $this->shopPicture = null;
        $this->successAlert();
        return $this->redirect("/my/shops/" . $this->shop->username, true);
    }
}