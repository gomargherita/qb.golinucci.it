<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

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
        'flag_prev_2022',
        'motivo_blocco_invio'
    ];

    protected $casts = [
        'dob' => 'date',
        'confirmation' => 'boolean',
        'with_accident' => 'boolean',
        'flag_prev_2022' => 'boolean',
        //'phone' => E164PhoneNumberCast::class . ':IT',
        'phone' => 'string',

    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function productClientDetails(): BelongsToMany
    {
            return $this->belongsToMany(
                ConditionalField::class,
                'product_client_details',
                'client_detail_id',
                'conditional_field_id',
            )
                ->withPivot('selected_field_value')
                ->withTimestamps();
    }

    public function getDobAttribute($value)
    {
        return $value;
    }

    /**
     * $select_field_flag se true restituisce il valore estraendolo dal json field_value, altrimenti restituisce il valore attuale
     */
    public function getConditionalFieldMatchById($id = null, $select_field_flag = true)
    {
        $productClientDetails = $this->productClientDetails()->where('conditional_field_id', $id)->first();
        $field = $productClientDetails->pivot->selected_field_value;
        if ($select_field_flag) {
            $data_array = json_decode($productClientDetails->field_value);
            $result = $data_array->$field;
        } else {
            $result = $field;
        }
        return $result;
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
        })->when($filters['with_accident'] ?? null, function ($query, $with_accident) {
            if ($with_accident === 'yes') {
                $query->where('client_details.with_accident', 1);
            } elseif ($with_accident === 'no'){
                $query->where('client_details.with_accident', 0);
            }
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
