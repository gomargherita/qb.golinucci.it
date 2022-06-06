<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Product extends Model implements HasMedia
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'detailed_description',
        'product_type_id',
        'conditions',
        'payments_description',
        'pre_declarations',
        'post_declarations',
        'hex_color',
        'active',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function conditionalFields(): BelongsToMany
    {
        return $this->belongsToMany(
            ConditionalField::class,
            'product_conditions',
            'product_id',
            'conditional_field_id'
        )->withTimestamps();
    }

    public function productOffers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function productCounters(): HasMany
    {
        return $this->hasMany(ProductCounter::class);
    }

    public function doprintCounters(): HasOne
    {
        return $this->hasOne(DoprintCounter::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function productDeclarations(): HasMany
    {
        return $this->hasMany(ProductDeclaration::class);
    }

    public function productSteps(): HasMany
    {
        return $this->hasMany(ProductStep::class);
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('name');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('products.name', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'description',
                'detailed_description',
                'product_type_id',
                'conditions',
                'payments_description',
                'pre_declarations',
                'post_declarations',
                'hex_color',
                'active',
            ]);
    }
}
