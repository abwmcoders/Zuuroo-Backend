<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'countryC_code',
        'first_name',
        'last_name',
        'transaction_ref',
        'id_number',
        'id_type',
        'date_of_birth',
        'verify_status'
    ];
}
