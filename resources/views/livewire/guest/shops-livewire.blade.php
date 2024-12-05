<div>
    <div class="min-h-screen pt-20 text-primary-900 bg-accent-500">

        <div class="flex flex-col justify-center w-full gap-3 px-8 pb-20 mx-auto lg:w-fit lg:px-12">

            <div class="flex flex-col items-start justify-between gap-4 lg:items-center lg:flex-row">
                <div class="w-full text-4xl text-center">
                    @if ($isMine)
                        My
                    @endif Shops
                </div>
                @if ($isMine)
                    <div>
                        <a role="button" href="{{ route('my.shops.create') }}" wire:navigate
                            class="block w-full text-white btn-accent btn btn-sm">Create
                            a new shop <i class="fa fa-plus"></i>
                        </a>

                    </div>
                @endif
            </div>
            @if (count($shops) === 0)
                @livewire('components.error-component-livewire', ['errorTitle' => 'No shop created yet', 'message' => 'Create one to get started'])
            @endif
            <div class="grid w-full grid-cols-1 gap-12 p-8 lg:px-12 xl:grid-cols-3">
                @foreach ($shops as $shopKey => $shop)
                    <a role="button" href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $shop->username }}"
                        wire:navigate wire:key='{{ $shopKey }}'
                        class="w-full mx-auto shadow-xl lg:w-96 card bg-base-100">

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
        </div>
        @if (!$isMine)
            <div class="text-4xl text-center">
                @if ($isMine)
                    My
                @endif Featured Products
            </div>
            <div class="grid grid-cols-1 gap-8 p-8 pb-20 lg:px-12 md:grid-cols-2 lg:grid-cols-5">
                @foreach ($featuredProducts as $k => $featuredProduct)
                    <div class="shadow-xl card bg-base-100">
                        <figure>
                            <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? ':productId' }}"
                                wire:navigate
                                class="w-full transition-all duration-500 bg-center bg-no-repeat bg-cover scale-[102%] hover:scale-[104%] bg-primary-900 h-48"
                                style="background-image: url({{ $featuredProduct->image_url }})"></a>
                        </figure>
                        <div class="flex flex-row py-4 justify-between !gap-0 card-body">
                            <div class="flex flex-col justify-between gap-0 h-min">
                                <div><a class="text-xs"
                                        href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $featuredProduct->shop->username }}"
                                        wire:navigate>{{ $featuredProduct->shop->name }}</a>
                                </div>
                                <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? ':productId' }}"
                                    wire:navigate class="overflow-hidden text-xl text-md max-h-16">
                                    {{ $featuredProduct->name }}
                                </a>
                                @if ($featuredProduct->description ?? false)
                                    <p>{{ $featuredProduct->description }}</p>
                                @endif
                                @if (count($featuredProduct->tags ?? []) > 0)
                                    <div class="justify-end ">
                                        @foreach ($featuredProduct->tags as $tag)
                                            <div class="badge badge-outline">{{ $tag->name }}</div>
                                        @endforeach
                                    </div>
                                @endif
                                <livewire:components.rating wire:key="{{ $k }}" sizeClass="w-3"
                                    :value="$featuredProduct->rating" />
                                <div class="text-lg">
                                    @money($featuredProduct->price)
                                </div>
                            </div>

                            <div class="flex flex-col justify-center gap-2 h-min">
                                <a role="button"
                                    href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? ':productId' }}"
                                    wire:navigate class="bg-primary-500 flex-1 hover:bg-[#eed295] btn">
                                    <i class="fa-solid fa-info"></i>
                                </a>
                                <button
                                    x-on:click="$wire.addToCart('{{ $featuredProduct->id }}'); $dispatch('cartUpdated')"
                                    class="btn bg-primary-600 flex-1 hover:bg-[#ebb94e] !text-primary-950">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <livewire:components.view-cart-button />
</div>
