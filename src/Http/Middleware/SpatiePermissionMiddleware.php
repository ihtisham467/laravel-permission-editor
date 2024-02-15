<?php

namespace Ihtisham467\LaravelPermissionEditor\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class SpatiePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('permissions')) {
            throw new \Exception('Spatie Laravel Permission package is not configured: missing roles/permissions DB tables');
        }

        return $next($request);
    }
}
