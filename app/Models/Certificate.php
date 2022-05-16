<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Certificate extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'cart_id',
        'product_id',
        'product_offer_id',
        'contract_id',
        'certificate_no',
        'certificate_doprint_no',
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
        'active'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'cart_id',
                'product_id',
                'product_offer_id',
                'contract_id',
                'certificate_no',
                'certificate_doprint_no',
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
                'active'
            ]);
    }
}
