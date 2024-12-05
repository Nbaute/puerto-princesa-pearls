<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpRateLimit extends Model
{
    use HasFactory;

    protected $fillable = ['ip_address', 'phone_number', 'total_send_count', 'send_count', 'expires_at'];
}
