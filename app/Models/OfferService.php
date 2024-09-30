<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'service_code',
        'service_category',
        'svalue',
        'service_state'
    ];
}
