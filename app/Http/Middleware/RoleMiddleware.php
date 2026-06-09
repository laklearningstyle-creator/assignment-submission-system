<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $roleMap = [
            'admin' => 1,
            'teacher' => 2,
            'student' => 3,
        ];

        $roleId = $roleMap[$role] ?? null;

        if (!$roleId || auth()->user()->role_id != $roleId) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
