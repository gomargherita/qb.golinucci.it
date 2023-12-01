<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class ProductCondition extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_id',
        'conditional_field_id',
    ];

    public function product(): belongsToMany
    {
        return $this->belongsToMany(ProductCondition::class);
        
    }
}
