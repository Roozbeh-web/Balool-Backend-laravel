<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'followed_user_id',
        'status'
    ];
    
    public $timestamps = false;
}
