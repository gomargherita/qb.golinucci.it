<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Renewal extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'old_cart_ref';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'old_cart_ref',
        'new_cart_ref',
    ];

    public function oldCart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'old_cart_ref', 'cart_ref');
    }

    public function newCart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'new_cart_ref', 'cart_ref');
    }
}
