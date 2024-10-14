<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CableSubscription extends Model
{
    use HasFactory;


    protected $fillable = [
        'provider_name',
        'provider_code',
        'country_code',
        'status',
    ];
}
