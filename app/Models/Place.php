<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cep',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'country',
        'category_id'
    ];
}
