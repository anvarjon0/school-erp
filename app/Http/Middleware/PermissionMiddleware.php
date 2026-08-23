<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Super admin has access to everything
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        if (!$request->user()->hasPermission($permission)) {
            abort(403, 'Sizda bu amalni bajarish huquqi yo\'q.');
        }

        return $next($request);
    }
}
