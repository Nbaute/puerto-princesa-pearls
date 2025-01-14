<?php

namespace App\Livewire\Guest;

use App\Livewire\AppComponent;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemHasCategory;
use App\Traits\FiltersTrait;
use App\Traits\MyCartTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Throwable;

class ViewProductLivewire extends AppComponent
{
    use FiltersTrait, MyCartTrait, WithFileUploads;
    public $product;
    public $name;
    public $description;
    public $price;
    public $categories = [];
    public $productPicture;
    public $canEdit = false;
    public function mount()
    {

        $this->checkMine();
        $productId = Route::current()->parameters()['productId'];
        $this->product = Item::find($productId);

        if (!empty($this->product)) {


            $this->name = $this->product->name;
            $this->description = $this->product->description ?? '';
            $this->price = $this->product->price;
            $this->categories = [];
            $this->syncFilters();
            $this->activeFilters = [];
            $this->product->tags->map(function ($tag) {
                $this->activeFilters[] = $tag->id;
            });

            $this->canEdit = $this->product->shop->user_id == Auth::id();
        }
    }
    public function render()
    {
        return view('livewire.guest.view-product-livewire');
    }

    public function saveChanges()
    {
        $data = [];
        if ($this->productPicture) {
            $data = $this->validate([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|min:1',
                'productPicture' => 'image|max:9999'
            ]);
        } else {
            $data =  $this->validate([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|min:1',
            ]);
        }
        try {
            DB::beginTransaction();
            $this->product->name = $data['name'];
            $this->product->description = $data['description'];
            $this->product->price = $data['price'];
            $this->product->save();
            if ($this->productPicture) {
                $this->product->clearMediaCollection('images');
                $this->product->addMedia($this->productPicture->getRealPath())->toMediaCollection('images');
                $this->product->refresh();
                $this->productPicture = null;
                $this->dispatch('productUpdated', $this->product);
            }
            ItemHasCategory::query()
                ->where('item_id', $this->product->id)
                ->whereNotIn('item_category_id', $this->activeFilters)
                ->forceDelete();
            foreach ($this->activeFilters as $activeFilter) {

                ItemHasCategory::query()->firstOrCreate(
                    [
                        'item_id' => $this->product->id,
                        'item_category_id' => $activeFilter,
                        'shop_id' => $this->product->shop_id
                    ]
                );
            }
            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            dd($t);
        }
        $this->product->refresh();
        $this->successAlert();
    }

    #[On('activeFilters')]
    public function getActiveFilters($filters)
    {
        $this->activeFilters = $filters;
    }

    #[On('confirmed')]
    public function confirmed()
    {
        $this->clearUserCart();
        $this->proceedAddToCart();
        $this->dispatch('cartUpdated', 'cartUpdated');
    }

    public function disableProduct()
    {
        $this->product->status = 'inactive';
        $this->product->save();
        $this->successAlert();
    }
    public function enableProduct()
    {
        $this->product->status = 'active';
        $this->product->save();
        $this->successAlert();
    }
    public function deleteProduct()
    {
        $username = $this->product->shop->username;
        $this->product->delete();
        $this->successAlert();
        $this->redirect("/my/shops/" . $username);
    }
}