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
        $role = $user->getRoleNames()->first();
        if (in_array($role, [Role::ROLE_SUPERADMIN, Role::ROLE_OPERATOR, Role::ROLE_AGENT])) {
            return $next($request);
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
    }
}
