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
        if($this->cart->product_id == 5){
            if(empty($this->payment_no) && $this->paid == 1){
                $productCounter = ProductCounter::where([
                    'product_id' => 1,
                    'payment_gateway_id' => $this->payment_gateway_id,
                    'year' => date("Y")
                ])->firstOrFail();
                $productCounter->counter = $productCounter->counter + 1;
                $productCounter->save();
                $this->payment_no = $productCounter->prefix . $productCounter->counter;
                $this->save();
            }
        }
        else{
            if(empty($this->payment_no) && $this->paid == 1){
                $productCounter = ProductCounter::where([
                    'product_id' => $this->cart->product_id,
                    'payment_gateway_id' => $this->payment_gateway_id,
                    'year' => date("Y")
                ])->firstOrFail();
                $productCounter->counter = $productCounter->counter + 1;
                $productCounter->save();
                $this->payment_no = $productCounter->prefix . $productCounter->counter;
                $this->save();
            }
        }
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
