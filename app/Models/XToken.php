<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XToken extends Model
{
    protected $fillable = [
        'user_id',
        'x_user_id',
        'access_token',
        'refresh_token',
        'expires_at',
    ];
}
