<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::attempt($request->only('email', 'password'));
        $user_role = auth()->user()->role;
        if($user_role == 'Customer'){
            return $next($request);
        }else{
            return response()->json([
                'status' => 'false',
                'text' => 'access denied'
            ]);
        }
    }
}
