<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use willvincent\Rateable\Rateable;

class Shop extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Rateable, WithFileUploads;

    protected $appends = ['image_url', 'rating'];
    protected $fillable = ['username', 'name'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('logos') ?? '';
    }

    public function getRatingAttribute()
    {
        return $this->averageRating() ?? 5;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}