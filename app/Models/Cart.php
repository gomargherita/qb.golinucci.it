<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        'retroactivity',
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
        'custom_end_date',
    ];

    protected $casts = [
        'renewal_date' => 'date',
        'retroactivity' => 'date',
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

    public function productStep(): BelongsTo
    {
        return $this->belongsTo(ProductStep::class);
    }

    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function signature(): HasOneThrough
    {
        return $this->hasOneThrough(Signature::class, Certificate::class);
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

    public function scopeOrderByDate($query)
    {
        $query->orderBy('created_at', 'desc');
    }

    public function getNameAttribute()
    {
        return $this->cart->cart_ref;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search_split = explode(" ", $search);

            foreach ($search_split as $sp){
            $query->where('carts.cart_ref', 'like', '%' . $sp . '%')
                // ->orWhere('client_details.first_name', 'like', '%' . $search . '%')
                // ->orWhere('client_details.last_name', 'like', '%' . $search . '%')
                ->orWhere('users.email', 'like', '%' . $sp . '%')
                ->orWhere('users.first_name', 'like', '%' . $sp . '%')
                ->orWhere('users.last_name', 'like', '%' . $sp . '%');
                // ->orWhereHas('productStep', function ($query) use ($search) {
                //     $query->where('step_name', 'like', '%' . $search . '%');
                // });
            }
        })->when($filters['steps'] ?? null, function ($query, $step) {
            $query->where('carts.product_step_id', $step);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            $user_id = auth()->user()->id;
            if(auth()->user()->owner == 1){
                $user_id = $cart->user_id;
            }
            // set renewal date empty // se vuoto non arriva da prima importazione via csv, quindi cliente nuovo
            if (empty($cart->renewal_date)) {
                // get renewal date if exists in previous cart
                $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->latest()->first();

                if ($last_prev_certificate) {
                    $cart->renewal_date = Carbon::parse($last_prev_certificate->end_validity_date)->toDateString();
                }
            }

            // se non vuoto può arrivare dall'excel di importazione rinnovi
            if (empty($cart->retroactivity)) {
                $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['retroactivity'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })->latest()
                    ->first();

                if ($last_prev_certificate) {
                    $cart->retroactivity = Carbon::parse($last_prev_certificate->retroactivity)->toDateString();
                }
            }
        });

        static::created(function ($cart) {

            DB::transaction(function () use ($cart) {
                // recuperare o creare anagrafica
                $clientDetails = ClientDetail::query()
                ->with(['productClientDetails' => function ($query) use ($cart) {
                    //$query->where('product_id', $cart->product->id);
                    }])

                    ->latest('id')
                    ->firstOrCreate([
                        'user_id' => $cart->user->id,
                    ], [
                        'first_name' => $cart->user->first_name,
                        'last_name' => $cart->user->last_name,
                    ]);

                // recuperare o creare product anagrafica
                $product = Product::find($cart->product_id);
                //if ($clientDetails->productClientDetails->count() != $product->conditionalFields->count()) {
                    $arrayDetails = array();
                    foreach($clientDetails->productClientDetails as $detail){
                        array_push($arrayDetails, $detail->id);
                    }

                    foreach ($product->conditionalFields as $conditionalField) {
                        if(!in_array($conditionalField->id, $arrayDetails)){
                            ProductClientDetail::create([
                                'client_detail_id' => $clientDetails->id,
                                'conditional_field_id' => $conditionalField->id,
                                'product_id' => $product->id,
                            ]);
                        }
                    }
                //}
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
                'custom_end_date',
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
