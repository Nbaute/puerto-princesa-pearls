<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemHasCategory extends Model
{
    protected $table = 'item_has_categories';
    protected $fillable = ['item_id', 'item_category_id', 'shop_id'];
    use SoftDeletes;
}
