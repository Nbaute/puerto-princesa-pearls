 <div>
     <div class="min-h-screen bg-center bg-no-repeat bg-cover hero" style="background-image: url(/images/hero-1.jpeg);">
         <div class="hero-overlay bg-opacity-30"></div>
         <div class="text-center text-white hero-content">
             <div class="max-w-md">
                 <h1 class="mb-5 text-5xl font-bold">Discover the Treasures of Puerto Princesa</h1>
                 <p class="mb-5">
                     Experience the timeless beauty of genuine Puerto Princesa pearls. Handcrafted with care and
                     inspired
                     by
                     the pristine waters of Palawan, these elegant pearls are perfect for every occasion. Shop now and
                     bring
                     home a piece of nature’s luxury!
                 </p>
                 <a href="{{ route('guest.shops') }}" wire:navigate class="btn btn-primary text-primary-900">Shop Now</a>
             </div>
         </div>
     </div>

     <div class="min-h-[calc(100dvh)] flex flex-col lg:flex-row items-stretch justify-stretch">
         <div class="hidden items-stretch order-2 w-full p-8 bg-primary-50 lg:order-1 lg:w-1/5 justify-stretch">

             <div class="w-full p-8 overflow-hidden h-min card bg-primary-300">


                 @livewire('components.filters-component-livewire', ['filters' => $filters, 'activeFilters' => $activeFilters])
                 <div
                     class=" transition-all bg-accent-400 duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%] size-full">
                 </div>
             </div>
         </div>

         <div class="order-1 w-full min-h-screen p-8 pt-10 lg:order-2 lg:w-full text-primary-900 bg-accent-500">
             <div class="text-4xl mb-4 text-center">Featured Products</div>

             <div class="grid grid-cols-1 gap-8 lg:p-8 md:grid-cols-2 lg:grid-cols-5">
                 @foreach ($featuredProducts as $featuredProduct)
                     <div class="shadow-xl card bg-base-100">
                         <figure>
                             <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? ':productId' }}"
                                 wire:navigate
                                 class="w-full transition-all duration-500 bg-center bg-no-repeat bg-cover scale-[102%] hover:scale-[104%] bg-primary-900 h-72"
                                 style="background-image: url({{ $featuredProduct->image_url }})"></a>
                         </figure>
                         <div class="flex flex-col !gap-0 justify-between card-body">
                             <div><a class="font-normal" href="/shops/{{ $featuredProduct->shop->username }}"
                                     wire:navigate>{{ $featuredProduct->shop->name }}</a>
                             </div>
                             <a href="/{{ $isMine ?? false ? 'my/' : '' }}shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? ':productId' }}"
                                 wire:navigate class="text-xl font-normal card-title">
                                 {{ $featuredProduct->name }}
                                 @if ($featuredProduct->is_new ?? false)
                                     <div class="text-sm badge badge-secondary">NEW</div>
                                 @endif
                             </a>
                             @if ($featuredProduct->description ?? false)
                                 <p>{{ $featuredProduct->description }}</p>
                             @endif
                             @if (count($featuredProduct->tags ?? []) > 0)
                                 <div class="justify-end my-2 card-actions">
                                     @foreach ($featuredProduct->tags as $tag)
                                         <div class="badge badge-outline">{{ $tag->name }}</div>
                                     @endforeach
                                 </div>
                             @endif

                             <livewire:components.rating :value="$featuredProduct->rating" />
                             <div class="text-xl">
                                 @money($featuredProduct->price)
                             </div>

                             <div class="flex flex-col justify-center gap-2">
                                 <a href="/shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? $featuredProduct->name }}"
                                     wire:navigate class="bg-primary-500 font-normal hover:bg-[#eed295] btn">
                                     View Details <i class="fa-solid fa-info"></i>
                                 </a>
                                 <button
                                     x-on:click="$wire.addToCart('{{ $featuredProduct->id }}'); $dispatch('cartUpdated')"
                                     class="btn bg-primary-600 font-normal hover:bg-[#ebb94e] !text-primary-950">
                                     Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                 </button>
                             </div>
                         </div>
                     </div>
                 @endforeach
             </div>
             <div class="flex flex-col justify-center gap-3 px-8 py-20">

                 <div class="text-xl text-center">Explore different pearl products from our leading shops</div>
                 <div class="flex justify-center">
                     <a href="{{ route('guest.shops') }}" wire:navigate class="btn btn-primary text-primary-900">Shop
                         Now</a>
                 </div>
             </div>
         </div>
     </div>

     <livewire:components.view-cart-button />
 </div>
