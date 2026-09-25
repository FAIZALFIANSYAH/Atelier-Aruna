<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Supports one or more roles, e.g. role:admin or role:cashier,admin
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            abort(403);
        }

        if (! in_array($user->role->name, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
