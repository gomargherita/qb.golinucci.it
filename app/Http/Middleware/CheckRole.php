<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Closure;

class CheckRole
{

    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $role = $user->hasRole(Role::ROLE_USER);
        if (!$role) {
            return $next($request);
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
    }
}
