<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $roles = explode('|', $role);
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            \Log::warning('403 DENIED. User ID: ' . ($request->user() ? $request->user()->id : 'Guest') . ' | Role: ' . ($request->user() ? $request->user()->role : 'None') . ' | Attempted to access URL: ' . $request->url() . ' | Required roles: ' . implode(',', $roles));
            abort(403, 'Unauthorized access to this portal.');
        }

        return $next($request);
    }
}
