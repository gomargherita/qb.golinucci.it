<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'cart_id',
        'payment_gateway_id',
        'payment_no',
        'paid',
        'total_payable',
        'transaction_ref',
        'paypal_status',
        'last_capture_at',
        'paid_at'
    ];

    protected $casts = [
        'paid' => 'boolean',
        'paid_at' => 'date',
        'last_capture_at' => 'datetime',
    ];


    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PaymentEvent::class);
    }

    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function updatePaymentPaymentNo(){
        if (empty($this->payment_no) && $this->paid == 1) {
            $productId = $this->cart->product_id == 5 ? 1 : $this->cart->product_id;
            $productCounter = $this->resolveProductCounter($productId);
            if (!$productCounter) {
                \Illuminate\Support\Facades\Log::error('payment.counter_missing', [
                    'payment_id' => $this->id,
                    'product_id' => $productId,
                    'payment_gateway_id' => $this->payment_gateway_id,
                    'year' => date('Y'),
                ]);
                $this->payment_no = 'TMP-' . $this->id;
                $this->save();
                return;
            }
            $productCounter->counter = $productCounter->counter + 1;
            $productCounter->save();
            $this->payment_no = $productCounter->prefix . $productCounter->counter;
            $this->save();
        }
    }

    private function resolveProductCounter(int $productId): ?ProductCounter
    {
        $year = date('Y');
        $counter = ProductCounter::where([
            'product_id' => $productId,
            'payment_gateway_id' => $this->payment_gateway_id,
            'year' => $year,
        ])->first();
        if ($counter) {
            return $counter;
        }

        $previous = ProductCounter::where([
            'product_id' => $productId,
            'payment_gateway_id' => $this->payment_gateway_id,
        ])->orderByDesc('year')->first();

        $counter = new ProductCounter();
        $counter->product_id = $productId;
        $counter->payment_gateway_id = $this->payment_gateway_id;
        $counter->year = $year;
        $counter->prefix = $previous
            ? preg_replace('/^\d{2}/', date('y'), (string) $previous->prefix)
            : ('QB' . $productId . '-');
        $counter->counter = 0;
        $counter->save();

        return $counter;
    }

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
                'payment_no',
                'paid',
                'total_payable',
                'transaction_ref',
                'paid_at'
            ]);
    }
}
