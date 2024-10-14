<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricityBillerName extends Model
{
    use HasFactory;

    protected $fillable = [
        'biller_name',
        'biller_code',
        'country_code',
        'status',
    ];
}
