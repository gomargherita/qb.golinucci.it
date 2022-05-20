<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Signature extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'cart_id',
        'procedure_id',
        'member_signer_id',
        'status',
    ];

    public function cart(): HasOneThrough
    {
        return $this->hasOneThrough(Cart::class, Certificate::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cart_id',
                'procedure_id',
                'member_signer_id',
                'status',
            ]);
    }
}
