<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirtimeProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'category_code',
        'country_code',
        'operator_code',
        'product_code',
        'product_name',
        'product_price',
        'loan_price',
        'send_value',
        'send_currency',
        'receive_value',
        'receive_currency',
        'commission_rate',
        'uat_number',
        'validity',
        'status'
    ];
}
