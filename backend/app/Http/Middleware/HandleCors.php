<?php

namespace App\Http\Middleware;

Illuminate\Foundation\Http\Middleware\HandleCors;

class HandleCors
{
    public function handle($request, $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', '*')
                 ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
                 ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                 ->header('Access-Control-Max-Age', '86400')
                 ->header('Access-Control-Allow-Credentials', 'true');

        if ($request->isMethod('OPTIONS')) {
            return response()->json(null, 204);
        }

        return $response;
    }
}
