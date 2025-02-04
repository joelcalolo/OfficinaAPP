<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return redirect()->route($request->user()->role . '.dashboard')
                ->with('error', 'Acesso não autorizado.');
        }

        return $next($request);
    }
}