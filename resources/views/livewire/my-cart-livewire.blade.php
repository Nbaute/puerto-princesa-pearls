<div class="min-h-[calc(100dvh)] bg-accent-500 p-8 lg:px-12">
    <div class="flex items-start justify-between pb-8">
        <div class="text-3xl ">My Cart</div>
        {{-- <div class="flex flex-col items-end justify-end gap-3">
            <div class="text-lg font-semibold">Subtotal: @money($subtotal)</div>
            <button class="btn-accent text-accent-50 btn">Checkout</button>
        </div> --}}
    </div>
    @if (count($cartItems) === 0)
        @livewire('components.error-component-livewire', ['errorTitle' => 'Cart is empty', 'message' => 'Add an item to your cart first.'])
    @endif




    <div class="grid grid-cols-1 gap-8 ">
        @foreach ($cartItems as $cartItemKey => $cartItem)
            @php
                $featuredProduct = $cartItem->item;
            @endphp
            <div wire:key='{{ $cartItemKey }}'
                class="flex flex-col justify-start gap-0 shadow-xl lg:flex-row overflow-clip rounded-xl bg-base-100">
                <figure>
                    <div class="lg:w-32  w-full transition-all duration-500 bg-center bg-no-repeat bg-cover scale-[102%] hover:scale-[104%] bg-primary-900 lg:h-full h-48"
                        style="background-image: url({{ $featuredProduct->image_url }})"></div>
                </figure>
                <div class="flex  flex-1 flex-col lg:flex-row py-4 justify-between !gap-0 card-body">
                    <div class="flex flex-col justify-between gap-0 h-min">
                        <div><a class="text-xs" href="/shops/{{ $featuredProduct->shop->username }}"
                                wire:navigate>{{ $featuredProduct->shop->name }}</a>
                        </div>
                        <h2 class="overflow-hidden text-md max-h-16">
                            {{ $featuredProduct->name }}
                        </h2>
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
                        <livewire:components.rating wire:key="{{ $cartItemKey }}" sizeClass="w-3" :value="$featuredProduct->rating" />
                        <div class="text-lg">
                            @money($featuredProduct->price)
                        </div>

                        {{-- <div class="join">
                            <button class="btn join-item">Button</button>
                            <button class="btn join-item">Button</button>
                            <button class="btn join-item">Button</button>
                        </div> --}}
                    </div>

                    <div class="flex flex-col justify-center gap-2 my-auto h-min">
                        <a role="button"
                            href="/shops/{{ $featuredProduct->shop->username }}/products/{{ $featuredProduct->id ?? ':productId' }}"
                            wire:navigate class="bg-primary-500 flex-1 hover:bg-[#eed295] btn">
                            <i class="fa-solid fa-info"></i>
                        </a>
                        {{-- <button x-on:click="$wire.addToCart('{{ $featuredProduct->id }}')"
                            class="btn bg-primary-600 flex-1 hover:bg-[#ebb94e] !text-primary-950">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button> --}}

                        {{-- {{ $cartItem->quantity }} --}}
                        {{-- <div class="flex-1 join">
                            <button class="btn join-item">-</button>
                            <input type="number" class="input input-primary"
                                wire:model='quantities.cart_item_{{ $cartItem->id }}' />
                            <button class="btn join-item">+</button>
                        </div> --}}
                        <div class="flex items-center justify-center flex-1 w-full gap-0 mx-auto ">
                            <button class="btn btn-sm btn-accent"
                                x-on:click="$wire.decrementQuantity({{ $cartItem->id }})"
                                {{ $cartItem->quantity <= 0 ? 'disabled' : '' }}>
                                -
                            </button>

                            <input type="number" x-on:input.lazy='$wire.updateQuantity({{ $cartItem->id }})'
                                class="text-center flex-1 input input-sm !border-0 active:!border-0 active:!ring-0 !outline-none !ring-0 !w-[80px] "
                                wire:model.lazy="quantities.cart_item_{{ $cartItem->id }}" min="1" />

                            <button class="btn btn-sm btn-accent"
                                x-on:click="$wire.incrementQuantity({{ $cartItem->id }})">
                                +
                            </button>
                        </div>

                        <div class="flex-1 mt-3 text-lg text-right">
                            @money($cartItem->quantity * $cartItem->item->price)
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($doCheckout)
        <div class="block p-6 py-8 my-8 bg-white shadow-xl card">

            <div class="mb-4 text-3xl">Checkout Information</div>

            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm text-gray-800">Full Name</label>
                    <div class="relative flex items-center">
                        <input wire:model="name" name="name" type="text" required
                            class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                            placeholder="Enter full name" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                            class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                            <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                            <path
                                d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                data-original="#000000"></path>
                        </svg>
                    </div>
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm text-gray-800">Address</label>
                    <div class="relative flex items-center">
                        <input wire:model="address" name="address" type="text" required
                            class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                            placeholder="Enter address" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                            class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                            <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                            <path
                                d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                data-original="#000000"></path>
                        </svg>
                    </div>
                    @error('address')
                        <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <div class="divider"></div>
            </div>

            <div class="flex flex-col items-stretch gap-8 lg:flex-row justify-stretch">
                <div class="flex-1 ">
                    <div class="font-semibold text-md">Delivery</div>
                    <div class="pt-2 space-y-2">
                        @foreach ($shippingOptions as $shippingOption)
                            <label class="flex flex-row gap-4">
                                <input x-on:change='$wire.setShippingOption("{{ $shippingOption['text'] }}")'
                                    type="radio" name="shippingOption" value="{{ $shippingOption['text'] }}"
                                    class="radio radio-accent" />
                                {{ $shippingOption['text'] }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex-1 ">
                    <div class="font-semibold text-md">Payment Method</div>
                    <div class="pt-2 space-y-2">
                        @foreach ($paymentMethods as $paymentMethod)
                            <label class="flex flex-row gap-4">
                                <input wire:change='setPaymentMethod("{{ $paymentMethod }}")' type="radio"
                                    name="paymentMethod" value="{{ $paymentMethod }}" class="radio radio-accent" />
                                {{ $paymentMethod }}
                            </label>
                        @endforeach
                    </div>
                </div>


            </div>


            <div class="flex justify-end ">
                <div class="flex flex-col items-stretch max-w-[400px] gap-3 mt-8 justify-stretch">
                    <div class="text-sm font-semibold text-right"><span class="font-normal">Subtotal:</span> <span
                            class="text-gray-700">@money($subtotal)</span>
                    </div>
                    @if ($shippingFee ?? 0 > 0)
                        <div class="text-sm font-semibold text-right"><span class="font-normal">Delivery Fee:</span>
                            <span class="text-gray-700">@money($shippingFee ?? 0)</span>
                        </div>
                    @endif
                    <div class="flex gap-4">
                        <button wire:click="cancelCheckout"
                            class="text-white bg-red-500 hover:bg-red-600 btn">Cancel</button>
                        <button {{ $shippingFee <= 0 ? 'disabled' : '' }} wire:click='placeOrder'
                            class="flex-1 btn-accent text-accent-50 btn">Place
                            order <div class="divider divider-accent"></div> (@money(($shippingFee ?? 0) + $subtotal))</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (!$doCheckout)
        <div class="flex justify-end">
            <div class="flex flex-col items-stretch max-w-[200px] gap-3 mt-8 justify-stretch">
                <div class="text-lg font-semibold">Subtotal: <span class="text-emerald-700">@money($subtotal)</span>
                </div>
                <button wire:click='checkoutCart' class="flex-1 btn-accent text-accent-50 btn">Checkout</button>
            </div>
        </div>
    @endif
</div>
