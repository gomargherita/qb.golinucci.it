<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductClientDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_detail_id',
        'conditional_field_id',
        'selected_field_value',
    ];
}
