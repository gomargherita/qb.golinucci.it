<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoprintCounter extends Model
{
    use HasFactory;

    public $fillable = [
        'product_id',
        'year',
        'prefix',
        'counter',
        'postfix'
    ];
}
