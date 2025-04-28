<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if the method exists and call it
        if (!method_exists($request->user(), $permission)) {
            abort(403, 'Unknown permission check method.');
        }

        if (!$request->user()->{$permission}()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
