<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsnoRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->expectsJson());
        // dd($request->header('Content-Type') !== 'application/json');
        // if (!$request->expectsJson() || $request->header('Accept') !== 'application/json') {
        //     return response()->json(['error' => 'Invalid request. Accept header must be application/json.'], 400);
        // }

        return $next($request);
    }
}
