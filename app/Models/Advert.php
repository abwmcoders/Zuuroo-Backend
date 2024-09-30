<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    use HasFactory;

    // Define the table name if it's not plural by default (Laravel assumes plural names)
    protected $table = 'advert';

    // Specify the fillable fields
    protected $fillable = [
        'title',
        'description',
        'fileName',
        'active',
    ];

    // Disable timestamps if you don't have `updated_at` or other timestamps besides `created_at`
    public $timestamps = false;

    // Optionally, you can define default values for attributes
    protected $attributes = [
        'active' => 0,
    ];
}
