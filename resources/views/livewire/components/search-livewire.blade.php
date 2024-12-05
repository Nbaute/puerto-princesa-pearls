<label x-data x-init="$nextTick(() => document.getElementById('q')?.focus())" class="flex items-center gap-2 input input-primary bg-primary-500">
    @if ($isSearchVisible)
        <input id="q" wire:input.debounce.500ms="fetchResults" wire:model='q' type="search"
            class="grow text-sm !border-0 placeholder:text-gray-600 !border-b !border-b-primary-900 focus:!border-b-primary-900 !ring-0 bg-primary-500"
            placeholder="Search">
    @endif
    <button @click="$wire.showSearch()" class="btn btn-primary text-primary-950 !shadow-none">
        <i class=" fa fa-search fa-fw"></i>
    </button>
</label>
