<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLink extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'token','expired_at','used_at','link'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
