<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toggle extends Model
{

    protected $fillable = [
        'user_id',
        'is_happy',
    ];

    use HasFactory;
}
