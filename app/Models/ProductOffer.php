<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOffer extends Model
{
    use HasFactory, SoftDeletes;

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
}
