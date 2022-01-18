<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if(auth()->user()->isAdmin()){
            return $next($request);
        }

        if (! $request->expectsJson()) {
            abort(403);
        }

        return Response::json([
            'message' => '403 Forbidden',
            'status' => 'error'
        ],403);
    }
}
