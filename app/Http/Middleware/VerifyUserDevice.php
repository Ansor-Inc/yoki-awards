<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyUserDevice
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        if ($request->user() &&
            $request->user()->currentAccessToken()->user_agent === $request->userAgent()
        ) {
            return $next($request);
        }

        return response(['message' => 'Unauthenticated'], 401);
    }
}
