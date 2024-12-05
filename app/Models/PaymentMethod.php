<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'name',
        'type',
        'is_auto',
        'is_active',
        'in_order',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id', 'id');
    }
}
