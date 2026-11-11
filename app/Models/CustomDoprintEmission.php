<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomDoprintEmission extends Model
{
    protected $fillable = [
        'status',
        'coverholder_ref',
        'contract_type',
        'product_name',
        'policy_text_version',
        'first_name',
        'last_name',
        'fiscal_code',
        'gender',
        'dob',
        'phone',
        'address',
        'city',
        'province',
        'country',
        'postal_code',
        'start_date',
        'end_date',
        'premium_net',
        'premium_accessories',
        'premium_tax',
        'premium_gross',
        'policy_schedule_path',
        'wording_path',
        'attached_clauses_path',
        'warranty_statement_path',
        'created_by',
    ];

    protected $casts = [
        'dob' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'premium_net' => 'decimal:2',
        'premium_accessories' => 'decimal:2',
        'premium_tax' => 'decimal:2',
        'premium_gross' => 'decimal:2',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
