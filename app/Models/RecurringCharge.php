<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringCharge extends Model
{
    use HasFactory;

    protected $fillable =   [
        'user_id',
        'user_email',
        'authorization_code',
        'account_name',
        'account_number',
        'bank_name',
        'country_code',
        'card_type',
        'last4',
        'exp_month',
        'exp_year',
        'bin',
        'channel',
        'signature',
        'reusable',
        'status',
    ];
}
