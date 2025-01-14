<div>
    <form wire:submit.prevent='saveChanges' class="w-full min-h-screen shadow-xl bg-base-100">
        <figure>
            <div class="overflow-hidden">
                <label for="productPicture"
                    class="w-full  block transition-all duration-500 bg-center bg-no-repeat bg-contain scale-[102%] hover:scale-[104%] bg-primary-900 h-96"
                    style="background-image: url({{ $productPicture?->temporaryUrl() ?? ($product?->image_url ?? 'https://placehold.co/500x500/000000/FFF?text=Click+to+upload+your+product%27s+photo') }})"></label>

            </div>
            <input wire:model='productPicture' type="file" class="hidden" id="productPicture" accept="image/*">
            @error('productPicture')
                <div class="text-xs text-center text-red-500">{{ $message ?? '' }}</div>
            @enderror

        </figure>
        <div class="flex flex-col !gap-0 justify-between card-body">
            <div><a class="font-normal" href="/my/shops/{{ $product?->shop->username }}"
                    wire:navigate>{{ $product?->shop->name }}</a>
            </div>

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
            @if ($product?->description ?? false)
                <p>{{ $product?->description ?? '' }}</p>
            @endif
            @if (count($product?->tags ?? []) > 0)
                <div class="justify-start my-3 card-actions">
                    @foreach ($product?->tags as $tag)
                        <div class="badge badge-outline">{{ $tag->name }}</div>
                    @endforeach
                </div>
            @endif

            <livewire:components.rating :value="$product?->rating ?? 5" />

            <div>
                {{-- <label class="block mb-2 text-sm text-gray-800">Price</label> --}}
                <div class="relative flex items-center">
                    <input wire:model="price" name="price" type="number" required
                        class="w-full px-4 py-3 text-xl  !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                        placeholder="Enter product's price" />
                    {{-- <i class="fa fa-shop"></i> --}}
                </div>
                @error('price')
                    <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                @enderror
            </div>
            <div> 
                <div class="relative flex items-center">
                    <input wire:model="description" name="description" type="text" required
                        class="w-full px-4 py-3 text-xl  !border-0 border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                        placeholder="Enter product's size info" />
                    {{-- <i class="fa fa-shop"></i> --}}
                </div>
                @error('description')
                    <span class="text-xs text-red-500">Size info error: {{ $message ?? 'An error occurred' }}</span>
                @enderror
            </div>

            <div class="flex justify-start w-fit">
                <button class="mx-auto btn btn-primary" type="submit">Save changes <i class="fa fa-save"></i></button>
            </div>

        </div>
    </form>
</div>
