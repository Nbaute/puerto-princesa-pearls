<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function payment_statuses()
    {
        return $this->hasMany(OrderPaymentStatus::class);
    }

    public function shipping_statuses()
    {
        return $this->hasMany(OrderShippingStatus::class);
    }
}