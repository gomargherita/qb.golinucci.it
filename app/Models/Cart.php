<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function updateCartStep(int $next_step)
    {
        $this->product_step_id = $next_step;
        return $this->save();
    }

    public function verifyAndGrabCart(User $user)
    {
        return Cart::query()
            ->where([
                'user_id' => $user->id,
                'cart_ref' => $this->cart_ref,
                'locked' => 'N'
            ])->first();
    }

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
