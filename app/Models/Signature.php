<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Signature extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'certificate_id',
        'procedure_id',
        'member_signer_id',
        'file_id',
        'status',
        'signature_link',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function cart(): HasOneThrough
    {
        return $this->hasOneThrough(Cart::class, Certificate::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'certificate_id',
                'procedure_id',
                'member_signer_id',
                'status',
            ]);
    }
}
