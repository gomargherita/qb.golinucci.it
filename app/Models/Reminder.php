<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'cart_id',
        'one_week_before',
        'two_weeks_after',
        'one_month_after',
        'created_at',
        'updated_at',
        'deleted_at',
        'one_week_after',
        'access_token_hash',
        'access_token_expires_at',
    ];

    /**
     * @deprecated KAN-4 — plaintext passwords wiped; column kept nullable for one release.
     * Do not put 'pw' back in $fillable.
     */
    protected $hidden = [
        'pw',
        'access_token_hash',
    ];

    protected $casts = [
        'access_token_expires_at' => 'datetime',
    ];
}
