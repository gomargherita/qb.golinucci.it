<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Cart extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_step_id',
        'product_offer_id',
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
        'indemnity_limit_patrimonial',
        'declarations_signature',
        'expired_at',
        'locked',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'product_id',
                'product_step_id',
                'product_offer_id',
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
                'indemnity_limit_patrimonial',
                'declarations_signature',
                'expired_at',
                'locked'
            ]);
    }
}
