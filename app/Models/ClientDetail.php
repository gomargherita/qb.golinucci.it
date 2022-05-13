<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientDetail extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'fiscal_code',
        'gender',
        'dob',
        'address',
        'city',
        'province',
        'country',
        'postal_code',
        'phone',
        'confirmation',
        'contact_ref',
        'version_ref',
        'note',
        'with_accident',
        'flag_prev_2022'
    ];

    protected $casts = [
        'confirmation' => 'boolean',
        'with_accident' => 'boolean',
        'flag_prev_2022' => 'boolean',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function getNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('client_details.first_name', 'like', '%' . $search . '%')
                ->orWhere('client_details.last_name', 'like', '%' . $search . '%')
                ->orWhere('users.first_name', 'like', '%' . $search . '%')
                ->orWhere('users.last_name', 'like', '%' . $search . '%');
            // ->orWhereHas('accounts', function($query) use ($search){
            //     $query->where('name', 'like', '%' . $search . '%');
            // });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'first_name',
                'last_name',
                'fiscal_code',
                'gender',
                'dob',
                'address',
                'city',
                'province',
                'country',
                'postal_code',
                'phone',
                'confirmation',
                'contact_ref',
                'version_ref',
                'note',
                'with_accident',
                'flag_prev_2022'
            ]);
    }
}
