<div>
    @if (!empty($product))
        <form wire:submit.prevent='saveChanges' class="w-full min-h-screen shadow-xl bg-accent-500">
            <div class="overflow-hidden">
                <figure>
                    <label for="productPicture"
                        class="w-full block transition-all duration-500 bg-center bg-no-repeat bg-contain scale-[102%] hover:scale-[104%] bg-primary-900 h-96"
                        style="background-image: url({{ $productPicture?->temporaryUrl() ?? $product->image_url }})"></label>
                    @if ($isMine)
                        <input wire:model='productPicture' type="file" class="hidden" id="productPicture"
                            accept="image/*">
                    @endif
                </figure>
            </div>
            <div class="flex flex-col !gap-0 justify-between card-body">
                <div><a class="font-normal"
                        href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $product->shop->username }}"
                        wire:navigate>{{ $product->shop->name }}</a>
                </div>
                @if (!$isMine)
                    <h2 class="font-normal card-title">
                        {{ $product->name }}
                        @if ($product->is_new ?? false)
                            <div class="badge badge-secondary">NEW</div>
                        @endif
                    </h2>
                @else
                    <div>
                        {{-- <label class="block mb-2 text-sm text-gray-800">Shop Name</label> --}}
                        <div class="relative flex items-center">
                            <input wire:model="name" name="name" type="text" required
                                class="w-full text-3xl px-4 py-3  !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                placeholder="Enter product's name" />
                            {{-- <i class="fa fa-shop"></i> --}}
                        </div>
                        @error('name')
                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                        @enderror
                    </div>
                @endif
                @if ($product->description ?? false)
                    <p>{{ $product->description }}</p>
                @endif
                @if (!$isMine)
                    @if (count($product->tags ?? []) > 0)
                        <div class="justify-start my-3 card-actions">
                            @foreach ($product->tags as $tag)
                                <div class="badge badge-outline">{{ $tag->name }}</div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="py-4">
                        @livewire('components.filters-component-livewire', ['filters' => $filters, 'activeFilters' => $activeFilters])
                    </div>
                @endif
                <livewire:components.rating :value="$product->rating" />
                @if (!$isMine)
                    <div class="text-xl">
                        @money($product->price)
                    </div>
                @else
                    <div>
                        {{-- <label class="block mb-2 text-sm text-gray-800">Shop Name</label> --}}
                        <div class="relative flex items-center py-4">
                            <input wire:model="price" name="price" type="number" required
                                class="w-full px-4 py-3 text-xl  !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                placeholder="Enter product's price" />
                            {{-- <i class="fa fa-shop"></i> --}}
                        </div>
                        @error('price')
                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-start w-full lg:w-fit">
                        <button class="flex-1 mx-auto btn btn-primary" type="submit">Save changes <i
                                class="fa fa-save"></i></button>
                    </div>


                    <br>
                    <br>
                    <br>
                    <div class="flex flex-row items-center gap-4">

                        @if ($product->status == 'active')
                            <button type="button" wire:click='disableProduct' class="btn btn-primary"> <i
                                    class="fa fa-times fa-fw"></i> Disable
                                product</button>
                        @elseif($product->status == 'inactive')
                            <button type="button" wire:click='enableProduct' class="text-white btn btn-success">
                                <i class="fa fa-check"></i> Enable
                                product</button>
                        @endif
                        <button type="button" wire:confirm wire:click='deleteProduct' class="text-white btn btn-error">
                            <i class="fa fa-trash fa-fw"></i>
                            Delete
                            product</button>
                    </div>
                @endif

                <div class="flex mt-3 flex-row w-full lg:max-w-[400px] justify-center gap-2 md:flex-col">
                    @if ($canEdit && !$isMine)
                        {{-- <a role="button" type="button"
                            href="/my/shops/{{ $product->shop->username }}/products/{{ $product->id }}" wire:navigate
                            class="btn flex-1 btn-primary font-normal  !text-primary-950">
                            <i class="fa-solid fa-pencil"></i>
                        </a> --}}
                    @endif
                    @if (!$isMine)
                        <button type="button"
                            x-on:click="$wire.addToCart('{{ $product->id }}'); $dispatch('cartUpdated')"
                            class="btn flex-1 bg-primary-600 font-normal hover:bg-[#ebb94e] !text-primary-950">
                            Add to Cart <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    @endif
                </div>
            </div>
        </form>
    @else
        @livewire('components.error-component-livewire', ['errorTitle' => 'Product not found', 'message' => "The product may have been removed or doesn't exist."])
    @endif
    <livewire:components.view-cart-button />
</div>
