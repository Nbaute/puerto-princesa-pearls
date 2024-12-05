<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use willvincent\Rateable\Rateable;

class Item extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia, Rateable;

    protected $appends = ['image_url'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', 1)->where('status', 'active')->whereHas('shop', fn($q) => $q->where('status', 'active'));
    }

    public function getRatingAttribute()
    {
        return $this->averageRating() ?? 5;
    }

    // public function categories()
    // {
    //     return $this->hasManyThrough(ItemCategory::class, ItemHasCategory::class, 'item_id', 'id', 'id', 'item_category_id');
    // }

    public function categories()
    {
        return $this->belongsToMany(ItemCategory::class, 'item_has_categories', 'item_id', 'item_category_id');
    }
    public function tags()
    {
        return $this->belongsToMany(ItemCategory::class, 'item_has_categories', 'item_id', 'item_category_id');
    }
}