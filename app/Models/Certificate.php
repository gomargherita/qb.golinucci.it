<?php

namespace App\Models;

use Carbon\Carbon;
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
        'cart_id',
        'product_id',
        'product_offer_id',
        'contract_id',
        'start_validity_date',
        'end_validity_date',
        'retroactivity',
        'certificate_no',
        'doprint_token',
        'doprint_current_step',
        'doprint_certificate_no',
        'active'
    ];

    protected $casts = [
        'start_validity_date' => 'date',
        'end_validity_date' => 'date',
        'retroactivity' => 'date',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    public function signature(): HasOne
    {
        return $this->hasOne(Signature::class);
    }

    public function setStartValidityDateAttribute($renewal_date)
    {
        if (empty($renewal_date)) {
            $renewal_date = $this->cart->payment->paid_at; 
        }

        $this->attributes['start_validity_date'] = $renewal_date;
        $this->attributes['end_validity_date'] = Carbon::parse($renewal_date)->addMonths($this->productOffer->months);
    }

    // se esistono certificati completati precedenti allora fa fede la retroattività di quelli
    public function setRetroactivityAttribute($retroactivity)
    {
        if(empty($retroactivity)){
            // nuovo cliente
            // data di pagamento - 10 anni
            $retroactivity = Carbon::parse($this->cart->payment->paid_at)->subYears(10);
        }
        
        $this->attributes['retroactivity'] = $retroactivity;
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
                $certificate->certificate_no = $productCounter->prefix . $productCounter->counter;
                $certificate->doprint_current_step = 'handshake'; // set first step after initialization
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cart_id',
                'product_id',
                'product_offer_id',
                'contract_id',
                'certificate_no',
                'doprint_token',
                'doprint_current_step',
                'doprint_certificate_no',
                'active'
            ]);
    }
}
