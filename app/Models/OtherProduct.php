<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'Category',
        'ServiceName',
        'variation_amount',
        'name',
        'serviceID',
        'variation_code',
        'convinience_fee',
        'fixedPrice',
    ];
}
