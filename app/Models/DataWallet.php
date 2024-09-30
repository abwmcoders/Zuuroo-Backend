<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_ref',
        'mobile_recharge',
        'user_id',
        'balance_bfo',
        'balance_after',
        'amount_debt'
    ];
}