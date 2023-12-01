<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductClientDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_detail_id',
        'conditional_field_id',
        'selected_field_value',
        'product_id',
    ];

    public function conditionalField(): BelongsTo
    {
        return $this->belongsTo(ConditionalField::class);
    }
}
