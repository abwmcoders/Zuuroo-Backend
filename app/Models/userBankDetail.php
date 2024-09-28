<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userBankDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'res_reference',
        'user_name',
        'user_email',
        'account_name',
        'account_number',
        'bank_name',
        'bank_code',
        'account_status'
    ];
}
