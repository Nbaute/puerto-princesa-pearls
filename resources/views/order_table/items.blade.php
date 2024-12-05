<div class="font-normal ">
    <div class="flex flex-col items-stretch gap-2 justify-stretch">
        @foreach ($row->items as $item)
            <div class="flex flex-wrap justify-between gap-4">
                <div class="bg-center bg-no-repeat bg-contain rounded-sm shrink-0 size-12"
                    style="background-image: url({{ $item->item->image_url }})">

                </div>
                <div class="flex-1 text-left">
                    {{ $item->quantity }} &times; <a target="_blank"
                        href="/shops/{{ $item->item->shop->username }}/products/{{ $item->item_id }}"
                        class=" text-primary-900">{{ $item->item->name }}
                        (@money($item->price))
                    </a>
                </div>
                <div> @money($item->amount)</div>
            </div>
        @endforeach

    </div>
</div>
