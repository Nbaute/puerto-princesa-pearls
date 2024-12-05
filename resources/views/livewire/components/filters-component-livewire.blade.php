<div class="flex flex-col gap-4 drop-shadow-2xl text-primary-950">
    @foreach ($filters as $filterKey => $filter)
        <div wire:key="{{now()->timestamp . 'filter_' . $filterKey}}">
            @if (count($filter->subcategories) > 0)
                <div class="py-2 font-semibold">{{ $filter->name }}</div>
                <div class="flex flex-wrap items-start gap-2">
                    @foreach ($filter->subcategories as $subcategoryKey => $subcategory)
                        <div  wire:key="{{ now()->timestamp . '_' . $filterKey . '_' . $subcategoryKey }}">
                            @if (!in_array($subcategory->id, $activeFilters))
                                <div role="button"
                                    x-on:click='$wire.toggleSubcategory({{ $subcategory->parent_id }},{{ $subcategory->id }})'
                                    class="select-none badge badge-outline ">{{ $subcategory->name }}
                                </div>
                            @else
                                <div role="button"
                                    x-on:click='$wire.toggleSubcategory({{ $subcategory->parent_id }},{{ $subcategory->id }})'
                                    class="select-none badge badge-outline bg-primary-900 text-primary-50 ">
                                    {{ $subcategory->name }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif 
        </div>
    @endforeach
    @if ($showApplyButton ?? false)
        <div class="pt-3">
            <button type="button"
                class="w-full btn btn-sm bg-primary-950 hover:bg-[#211710] text-primary-50 btn-outline">Apply
                filters</button>
        </div>
    @endif
</div>
