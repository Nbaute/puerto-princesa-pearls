<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPaymentStatus extends Model
{
    use SoftDeletes;
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}