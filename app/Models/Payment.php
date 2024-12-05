<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ref_no',
        'amount',
        'note',
        'note_ref',
        'paid_at',
        'failed_at',
        'payment_method_id',
        'payment_ref',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function payment_method()
    {
        return $this->hasOne(PaymentMethod::class);
    }
}
