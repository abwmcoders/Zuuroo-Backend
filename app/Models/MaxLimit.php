<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'topup',
        'limit_value',
        'admin',
    ];
}
