<div>
    <form wire:submit.prevent="onShopPictureUpload" class="bg-accent-200">
        @if (empty($shop))
            @livewire('components.error-component-livewire', ['message' => 'Shop not found'])
        @else
            <div class="min-h-[calc(100dvh)] flex flex-col lg:flex-row items-stretch justify-stretch">
                <div class="items-stretch order-2   w-full p-8 h-min lg:order-1 lg:w-1/4 justify-stretch">

                    <div class="w-full p-8 overflow-hidden card bg-primary-300">


                        @livewire('components.filters-component-livewire', ['filters' => $filters, 'activeFilters' => $activeFilters])
                        <div class=" transition-all duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%] size-full"
                            style="background-image: url({{ $shop->image_url }})">
                        </div>
                    </div>
                </div>
                <div class="w-full p-8 lg:order-1 lg:w-3/4">
                    <div class="relative">
                        <figure class="overflow-hidden bg-primary-300 rounded-xl">
                            <div class=" transition-all duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%]  h-72"
                                style="background-image: url({{ $shop->image_url }})"></div>


                        </figure>
                        <label for="shopPicture"
                            class="absolute left-[calc(50%-72px)] lg:left-4 overflow-hidden border-8 border-white rounded-full lg:ml-4 top-48 lg:top-60 size-36 bg-primary-500">
                            <div class="  size-full  transition-all duration-500 bg-center bg-no-repeat bg-cover scale-[100%] hover:scale-[102%] bg-primary-900"
                                style="background-image: url({{ $shopPicture?->temporaryUrl() ?? $shop->image_url }})">
                            </div>
                        </label>



                    </div>

                    <div class="p-6 mt-5 lg:!mt-0 lg:ml-40">
                        <div class="flex flex-col items-center gap-4 lg:flex-row">


                            <div class="flex flex-col gap-1 {{ $isMine ? 'border' : '' }} w-fit">
                                @if (!$isMine)
                                    <div class="text-3xl text-center text-primary-950">{{ $shop->name }}</div>

                                    <div class="text-sm text-center text-gray-500">{{ $shop->username }}</div>
                                @endif
                                @if ($isMine)
                                    <div>
                                        {{-- <label class="block mb-2 text-sm text-gray-800">Shop Name</label> --}}
                                        <div class="relative flex items-center">
                                            <input wire:model="name" name="name" type="text" required
                                                class="w-fit text-3xl px-4 py-3 !border-0 text-gray-800 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                                placeholder="Enter shop name" />
                                            {{-- <i class="fa fa-shop"></i> --}}
                                        </div>
                                        @error('name')
                                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        {{-- <label class="block mb-2 text-sm text-gray-800">Shop Name</label> --}}
                                        <div class="relative flex items-center">
                                            <input wire:model="username" name="username" type="text" required
                                                class="w-full px-4 py-3 text-sm  text-gray-500 !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                                placeholder="Enter shop username" />
                                            {{-- <i class="fa fa-shop"></i> --}}
                                        </div>
                                        @error('username')
                                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        {{-- <label class="block mb-2 text-sm text-gray-800">Social Media LInk</label> --}}
                                        <div class="relative flex items-center">
                                            <input wire:model="link" name="link" type="text" required
                                                class="w-full px-4 py-3 text-sm  text-gray-500 !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                                placeholder="Enter shop's social media link" />
                                            {{-- <i class="fa fa-shop"></i> --}}
                                        </div>
                                        @error('link')
                                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        {{-- <label class="block mb-2 text-sm text-gray-800">Social Media LInk</label> --}}
                                        <div class="relative flex items-center">
                                            <textarea rows="3" wire:model="paymentInstructions" name="paymentInstructions" type="text" required
                                                class="w-full px-4 py-3 text-sm  text-gray-500 !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                                placeholder="Enter shop's payment instructions"></textarea>
                                            {{-- <i class="fa fa-shop"></i> --}}
                                        </div>
                                        @error('link')
                                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>


                            @if ($isMine)
                                <div class="p-8 w-fit">
                                    <input wire:model="shopPicture" type="file" src="" alt=""
                                        class="hidden" id="shopPicture" accept="image/*" />
                                    @error('shopPicture')
                                        <div class="text-xs text-center text-red-500 error">{{ $message }}</div>
                                    @enderror
                                    <div class="flex justify-center">
                                        <button class="mx-auto btn btn-xs btn-primary" type="submit">Save changes <i
                                                class="fa fa-save"></i></button>
                                    </div>
                                </div>
                            @endif



                        </div>
                        @if ($isMine)
                            <div class="pt-8">
                                @if ($shop->status == 'active')
                                    <button type="button" wire:click='disableShop' class="btn btn-primary"> <i
                                            class="fa fa-times fa-fw"></i> Disable
                                        shop</button>
                                @elseif($shop->status == 'inactive')
                                    <button type="button" wire:click='enableShop' class="text-white btn btn-success">
                                        <i class="fa fa-check"></i> Enable
                                        shop</button>
                                @endif
                                <button type="button" wire:confirm wire:click='deleteShop'
                                    class="text-white btn btn-error">
                                    <i class="fa fa-trash fa-fw"></i>
                                    Delete
                                    shop</button>
                            </div>
                        @endif
                        @livewire('components.rating', ['value' => 5, 'maxStars' => 5, 'sizeClass' => 'w-4'])
                    </div>



                    <div class="border-b"></div>

                    <div class="min-h-screen pt-8 text-primary-900 ">
                        <div class="flex flex-col items-start justify-start gap-8 px-8 ">
                            <div class="text-xl font-semibold text-left ">Products</div>
                            @if ($isMine)
                                <div>
                                    <a href="{{ route('my.products.create', [
                                        'username' => $shop->username,
                                    ]) }}"
                                        wire:navigate class="text-white btn-accent btn btn-sm">
                                        Create
                                        a new product <i class="fa fa-plus"></i></a>

                                </div>
                            @endif
                        </div>
                        @if (count($products) === 0)
                            <div class="px-8 pt-4">No products yet.</div>
                        @endif
                        <div class="grid grid-cols-1 gap-8 pt-4 border-red-400 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($products as $productKey => $product)
                                <div wire:key="{{  $productKey}}" class="shadow-xl card bg-base-100">
                                    <figure>
                                        <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $product->shop->username }}/products/{{ $product->id ?? ':productId' }}"
                                            wire:navigate
                                            class="w-full transition-all duration-500 bg-center bg-no-repeat bg-contain scale-[102%] hover:scale-[104%] bg-primary-900 h-72"
                                            style="background-image: url({{ $product->image_url }})"></a>
                                    </figure>
                                    <div class="flex flex-col !gap-0 justify-between card-body">
                                        <div><a class="font-normal"
                                                href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $product->shop->username }}"
                                                wire:navigate>{{ $product->shop->name }}</a>
                                        </div>
                                        <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $product->shop->username }}/products/{{ $product->id ?? ':productId' }}"
                                            wire:navigate class="text-xl font-normal card-title">
                                            {{ $product->name }}
                                            @if ($product->is_new ?? false)
                                                <div class="badge badge-secondary">NEW</div>
                                            @endif
                                        </a>
                                        @if ($product->description ?? false)
                                            <p>{{ $product->description }}</p>
                                        @endif
                                        @if (count($product->tags ?? []) > 0)
                                            <div class="justify-end card-actions">
                                                @foreach ($product->tags as $tagKey => $tag)
                                                    <div wire:key="{{now()->timestamp . '_' . $tagKey }}" class="badge badge-outline">{{ $tag->name }}</div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <livewire:components.rating :value="$product->rating" />
                                        <div class="text-xl">
                                            @money($product->price)
                                        </div>

                                        <div class="flex flex-col justify-center gap-2 lg:flex-row">
                                            <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $product->shop->username }}/products/{{ $product->id ?? $product->name }}"
                                                wire:navigate
                                                class="bg-primary-500 font-normal hover:bg-[#eed295] btn">
                                                View Details <i class="fa-solid fa-info"></i>
                                            </a>
                                            @if (!$isMine)
                                                <button type="button"
                                                    x-on:click="$wire.addToCart('{{ $product->id }}'); $dispatch('cartUpdated')"
                                                    class="btn bg-primary-600 font-normal hover:bg-[#ebb94e] !text-primary-950">
                                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </form>
    @livewire('components.view-cart-button')
</div>
