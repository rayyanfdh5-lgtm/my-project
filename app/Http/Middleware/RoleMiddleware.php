<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {

        $user = $request->user()?->fresh();
        if (! $user) {
            abort(403, 'No user found');
        }

        if (! $user->hasRole($role)) {
            Log::warning('Unauthorized role', [
                'expected' => $role,
                'actual' => $user->role instanceof UsersRole ? $user->role->value : $user->role,
            ]);
            abort(403);
        }

        return $next($request);
    }
}
