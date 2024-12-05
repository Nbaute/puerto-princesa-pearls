<?php

namespace App\Livewire\Components;

use App\Models\ItemCategory;
use App\Traits\FiltersTrait;
use Livewire\Component;

class FiltersComponentLivewire extends Component
{
    use FiltersTrait;

    public $filters;
    public $activeFilters = [];

    public function syncFilters()
    {

        $this->filters = ItemCategory::query()->whereNull('parent_id')->get();
    }

    public function toggleSubcategory($parentId, $subcategoryId)
    {
        if (in_array($subcategoryId, $this->activeFilters)) {
            $this->activeFilters = array_filter(
                $this->activeFilters,
                fn($filter) => $filter !== $subcategoryId
            );
        } else {

            $this->activeFilters[] = $subcategoryId;
        }
        $this->dispatch('activeFilters', $this->activeFilters);
    }

    public function getActiveFilters()
    {
        return $this->activeFilters;
    }
    public function mount() {}
    public function render()
    {
        return view('livewire.components.filters-component-livewire');
    }
}