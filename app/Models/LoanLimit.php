<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'labelName',
        'percentage',
        'status',
    ];
}
