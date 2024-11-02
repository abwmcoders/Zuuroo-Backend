<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCardDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_name',
        'authorization_code',
        'bank',
        'bin',
        'brand',
        'card_type',
        'country_code',
        'exp_month',
        'exp_year',
        'last4',
        'reusable',
        'signature',
    ];
}
