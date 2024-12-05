<div class="bg-accent-200">

    <div class="min-h-[calc(100dvh)] flex flex-col lg:flex-row items-stretch justify-stretch">
        <div class="items-stretch order-2 hidden w-full p-8 h-min lg:order-1 lg:w-1/4 justify-stretch">

            <div class="w-full p-8 overflow-hidden card bg-primary-300">

                @if ($shopPicture and method_exists($shopPicture, 'getTemporaryUrl'))
                    <div class=" transition-all duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%] size-full"
                        style="background-image: url({{ $shopPicture?->getTemporaryUrl() ?? ($shop?->image_url ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode($name)) }})">
                    </div>
                @else
                    <div class=" transition-all duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%] size-full"
                        style="background-image: url({{ $shop?->image_url ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode($name) }})">
                    </div>
                @endif
            </div>
        </div>
        <form wire:submit.prevent="onShopPictureUpload" class="w-full p-8 lg:order-1 ">
            <div class="relative">
                <figure class="overflow-hidden bg-primary-300 rounded-xl">
                    @if ($shopPicture and method_exists($shopPicture, 'getTemporaryUrl'))
                        <div class=" transition-all duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%]  h-72"
                            style="background-image: url({{ $shopPicture?->getTemporaryUrl() ?? ($shop?->image_url ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode($name)) }})">
                        </div>
                    @else
                        <div class=" transition-all duration-500 opacity-90 bg-center blur-2xl bg-no-repeat bg-cover scale-[100%] hover:scale-[102%]  h-72"
                            style="background-image: url({{ $shop?->image_url ?? 'https://ui-avatars.com/api/?background=random&name=' . urlencode($name) }})">
                        </div>
                    @endif


                </figure>
                <label for="shopPicture"
                    class="absolute ml-4 overflow-hidden border-8 border-white rounded-full top-48 lg:top-60 size-36 bg-primary-500">
                    <div class="  size-full  transition-all duration-500 bg-center bg-no-repeat bg-cover scale-[100%] hover:scale-[102%] bg-primary-900"
                        style="background-image: url({{ $shopPicture?->temporaryUrl() ?? ($shop?->image_url ?? 'https://placehold.co/500x500/000000/FFF?text=Click+to+upload+your+shop%27s+photo.') }})">
                    </div>

                </label>



            </div>

            <div class="p-6 mt-10 lg:mt-0 lg:ml-40">
                <div class="flex flex-col items-center gap-4 lg:flex-row">


                    <div class="flex flex-col gap-1 border w-fit">

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
                                <textarea rows=8 wire:model="paymentInstructions" name="paymentInstructions" type="text" required
                                    class="w-full px-4 py-3 text-sm  text-gray-500 !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                    placeholder="Enter shop's payment instructions"></textarea>
                                {{-- <i class="fa fa-shop"></i> --}}
                            </div>
                            @error('link')
                                <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- <template x-if="canEdit">

                            <i class="fa fa-fw fa-sm text-primary-950 fa-pencil"></i>

                        </template> --}}

                    <div class="p-8 w-fit">
                        <input wire:model="shopPicture" type="file" class="hidden" id="shopPicture" accept="image/*">
                        @error('shopPicture')
                            <div class="text-xs text-center text-red-500 error">{{ $message }}</div>
                        @enderror
                        <div class="flex justify-center">
                            <button class="mx-auto btn btn-xs btn-primary" type="submit">Save changes <i
                                    class="fa fa-save"></i></button>
                        </div>
                    </div>

                </div>
                @livewire('components.rating', ['value' => 5, 'maxStars' => 5, 'sizeClass' => 'w-4'])
            </div>


        </form>
    </div>
    @livewire('components.view-cart-button')
</div>
