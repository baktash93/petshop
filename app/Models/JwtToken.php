<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'user_id',
        'token_title',
        'permissions',
        'restrictions',
        'refreshed_at',
        'last_used_at',
        'expires_at'
    ];
}
