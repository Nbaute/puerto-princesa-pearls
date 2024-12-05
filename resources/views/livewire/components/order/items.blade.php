<div class="font-normal ">
    <div class="flex flex-col items-stretch gap-2 justify-stretch">
        @foreach ($row->items as $item)
            <div class="flex justify-between gap-4">
                <div class="bg-center bg-no-repeat bg-contain rounded-sm size-12"
                    style="background-image: url({{ $item->item->image_url }})">

                </div>
                <div class="flex-1 text-left">
                    {{ $item->quantity }} &times; <span class=" text-primary-900">{{ $item->item->name }}</span>
                </div>
                <div> @money($item->amount)</div>
            </div>
        @endforeach

    </div>
</div>
