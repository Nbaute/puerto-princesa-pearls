<div class="p-6 pt-8 lg:px-8">
    @if (!empty($q))
        <div class="text-3xl font-semibold text-center">Search Results</div>
        <div class="text-xl font-semibold ">Shops</div>
        @if (count($shops) > 0)
            <div class="grid w-full grid-cols-1 gap-12 p-8 lg:px-12 xl:grid-cols-3">
                @foreach ($shops as $shopKey => $shop)
                    <a role="button" href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $shop->username }}" wire:navigate
                        wire:key='{{ $shopKey }}' class="w-full mx-auto shadow-xl lg:w-96 card bg-base-100">

                        <figure>
                            <div class=" transition-all duration-500 bg-center bg-no-repeat bg-cover scale-[102%] hover:scale-[104%] bg-primary-900 w-full h-48"
                                style="background-image: url({{ $shop->image_url }})"></div>
                        </figure>
                        <div class="py-4 text-xl text-center card-body">
                            {{ $shop->name }}
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-sm">No shops found.</div>
        @endif

        <div class="text-xl font-semibold ">Products</div>
        @if (count($products) > 0)
            <div class="grid grid-cols-1 gap-8 pt-4 border-red-400 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $productKey => $product)
                    <div wire:key="{{ $productKey }}" class="shadow-xl card bg-base-100">
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
                                        <div wire:key="{{ now()->timestamp . '_' . $tagKey }}"
                                            class="badge badge-outline">
                                            {{ $tag->name }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <livewire:components.rating :value="$product->rating" />
                            <div class="text-xl">
                                @money($product->price)
                            </div>

                            <div class="flex flex-col justify-center gap-2 lg:flex-row">
                                <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $product->shop->username }}/products/{{ $product->id ?? $product->name }}"
                                    wire:navigate class="bg-primary-500 font-normal hover:bg-[#eed295] btn">
                                    View Details <i class="fa-solid fa-info"></i>
                                </a>
                                <button type="button"
                                    x-on:click="$wire.addToCart('{{ $product->id }}'); $dispatch('cartUpdated')"
                                    class="btn bg-primary-600 font-normal hover:bg-[#ebb94e] !text-primary-950">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-sm">No products found.</div>
        @endif

    @endif
</div>
