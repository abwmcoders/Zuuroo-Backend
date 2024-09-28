<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'operator_name',
        'operator_code',
        'validation_regex',
        'logo_url',
        'status'
    ];
}
