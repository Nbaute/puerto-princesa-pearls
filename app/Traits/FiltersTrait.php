<?php

namespace App\Traits;

use App\Models\ItemCategory;

trait FiltersTrait
{

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
    }

    public function getActiveFilters()
    {
        return $this->activeFilters;
    }
}
