<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentGateway extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'public',
    ];

    public static function electronicMaxEur(): float
    {
        return (float) config('payments.electronic_max_eur', 1000);
    }

    public static function allowsElectronic($amount): bool
    {
        return (float) $amount <= static::electronicMaxEur();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'public'
            ]);
    }
}
