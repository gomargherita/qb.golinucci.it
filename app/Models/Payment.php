<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'cart_id',
        'payment_gateway_id',
        'paid',
        'total_payable',
        'transaction_ref',
        'paid_at'
    ];

    protected $casts = [
        'paid' => 'boolean',
        'paid_at' => 'date'
    ];

    public function getPaidAtAttribute($value)
    {
        return $value;
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    // TODO da chiamare su payment via pannello quando ok pagamento bonifico bancario
    public function updatePaymentStatus()
    {
        $this->paid = 1;
        $this->paid_at = now();
        $this->save();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cart_id',
                'payment_gateway_id',
                'paid',
                'total_payable',
                'transaction_ref',
                'paid_at'
            ]);
    }
}
