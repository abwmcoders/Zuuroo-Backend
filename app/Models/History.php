<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'purchase',
        'country_code',
        'operator_code',
        'product_code',
        'transfer_ref',
        'phone_number',
        'distribe_ref',
        'selling_price',
        'cost_price',
        'receive_value',
        'send_value',
        'receive_currency',
        'commission_applied',
        'startedUtc',
        'completedUtc',
        'processing_state',
        'purchase'
    ];
}
