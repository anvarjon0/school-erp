<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Super admin has access to everything
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        if (!$request->user()->hasAnyRole($roles)) {
            abort(403, 'Sizda bu sahifaga kirish huquqi yo\'q.');
        }

        return $next($request);
    }
}
