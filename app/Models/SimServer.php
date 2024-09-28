<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_code',
        'sim_server',
        'client_id',
        'client_secret',
        'access_token',
        'public_key',
        'secret_key'
    ];
}
