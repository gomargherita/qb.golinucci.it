<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenewalOtp extends Model
{
    protected $fillable = [
        'cart_id',
        'user_id',
        'code_hash',
        'expires_at',
        'attempts',
        'consumed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
