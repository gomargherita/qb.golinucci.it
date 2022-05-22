<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class ConditionalField extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'field_value' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'field_name',
                'field_type',
                'field_value',
                'only_client_details'
            ]);
    }
}
