<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable =[
        'assessment',
        'description',
        'details',
        'user_id',
        'place_id'
    ];

    /**
     * Set here all attributes that should be cast // devem ser convertidos
     * 
     */
    protected $casts = [
        'details' => 'array'
    ];


}
