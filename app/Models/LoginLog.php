<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $table = 'logins';

    protected $casts = [
        'logged_in_at' => 'datetime',
    ];

    protected $fillable = ['user_id', 'logged_in_at', 'ip_address', 'user_agent'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
