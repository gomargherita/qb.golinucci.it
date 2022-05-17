<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($certificate) {
            if (empty($certificate->certificate_no)) {
                $productCounter = ProductCounter::where([
                    'product_id' => $certificate->cart->product_id,
                    'payment_gateway_id' => $certificate->cart->payment->payment_gateway_id,
                    'year' => date("Y")
                ])->firstOrFail();
                $productCounter->counter = $productCounter->counter + 1;
                $productCounter->save();
                $certificate_no = $productCounter->prefix . $productCounter->counter;
                $certificate->certificate_no = $certificate_no;
            }
        });
    }

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
