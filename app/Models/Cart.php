<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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

    protected $casts = [
        'renewal_date' => 'date'
    ];

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            // set renewal date empty // se vuoto non arriva da prima importazione via csv, quindi cliente nuovo
            if (empty($cart->renewal_date)) {
                // get renewal date if exists in previous cart
                $last_prev_certificate = Certificate::select(['end_validity_date'])->where([
                    // controllo su product
                    'product_id' => $cart->product->id,
                ])->latest()->first();

                if ($last_prev_certificate) {
                    $cart->renewal_date = Carbon::parse($last_prev_certificate->end_validity_date)->toDateString();
                }
            }
        });

        static::created(function ($cart) {

            DB::transaction(function () use ($cart) {
                // recuperare o creare anagrafica
                $clientDetails = ClientDetail::query()
                    // ->with('productClientDetails:field_name')
                    ->latest('id')
                    ->firstOrCreate([
                        'user_id' => $cart->user->id,
                    ], [
                        'first_name' => $cart->user->first_name,
                        'last_name' => $cart->user->last_name,
                    ]);

                // recuperare o creare product anagrafica
                $product = Product::find($cart->product_id);
                if ($clientDetails->productClientDetails->count() != $product->conditionalFields->count()) {
                    foreach ($product->conditionalFields as $conditionalField) {
                        ProductClientDetail::create([
                            'client_detail_id' => $clientDetails->id,
                            'conditional_field_id' => $conditionalField->id,
                        ]);
                    }
                }
            });
        });
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
