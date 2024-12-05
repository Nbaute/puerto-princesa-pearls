<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Model
{
    protected $table = 'item_categories';
    protected $with = ['subcategories'];

    use SoftDeletes;

    public function subcategories()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_has_categories', 'category_id', 'item_id');
    }
}
