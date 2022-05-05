<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_step_id',
        'cart_start_time',
        'cart_expiry_time',
        'locked',
        'cart_ref',
        'renewal_date',
        'total_payable',
        'admin_fee',
        'gross_premium',
        'tax',
        'net_premium',
        'accessory',
        'net_plus_access_premium',
        'indemnity_limit',
    ];
}
