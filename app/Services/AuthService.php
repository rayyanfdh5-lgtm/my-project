<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function login(array $credentials): ?string
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            LoginLog::create([
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'logged_in_at' => now(),
            ]);

            return match ($user->role->value) {
                'admin' => route('admin.dashboard'),
                'user' => route('user.dashboard'),
                default => '/',
            };

        }

        return null;
    }
}
