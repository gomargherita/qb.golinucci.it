<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductOffer extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function conditionalFields(): BelongsToMany
    {
        return $this->belongsToMany(
            ConditionalField::class,
            'product_offer_conditions',
            'product_offer_id',
            'conditional_field_id'
        )
            ->withPivot('selected_field_value')
            ->withTimestamps();
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'product_id',
                'contract_id',
                'name',
                'sku',
                'total_payable',
                'admin_fee',
                'gross_premium',
                'tax',
                'net_premium',
                'accessory',
                'net_plus_access_premium',
                'description',
                'indemnity_limit',
                'indemnity_limit_patrimonial',
                'flag_prev_2022',
                'months',
                'active',
                'MUP',
            ]);
    }
}
