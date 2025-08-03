<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordReset extends Model
{
    protected $fillable = ['email', 'code', 'expires_at'];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
