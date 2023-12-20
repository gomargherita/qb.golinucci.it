<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Exists;
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

        //se il prodotto è medici esegui sempre l'if
        if($this->cart->product->id == 1){
            $this->attributes['end_validity_date'] = Carbon::parse($renewal_date)->addMonths($this->productOffer->months);
        }
        
        else if(($this->cart->product->id == 2 || $this->cart->product->id == 3 ||$this->cart->product->id == 4) && !array_key_exists("end_validity_date", $this->attributes)){
            if($this->productOffer->months == 12){
                $this->attributes['end_validity_date'] = Carbon::parse($renewal_date)->endOfYear();
            }
            else{
                $this->attributes['end_validity_date'] = Carbon::parse($renewal_date)->addMonths($this->productOffer->months);
            }
        }
        
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cart_id',
                'product_id',
                'product_offer_id',
                'contract_id',
                'doprint_token',
                'doprint_current_step',
                'doprint_certificate_no',
                'active'
            ]);
    }
}
