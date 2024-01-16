<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Buyer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_role = auth()->user()->role;
        if ($user_role == 'Customer') {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'false',
                'text' => 'access denied'
            ]);
        }
    }
}
