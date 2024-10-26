<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CablePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan',
        'price',
        'provider_code',
    ];

    protected $casts = [
        'channels' => 'array',
    ];
}
