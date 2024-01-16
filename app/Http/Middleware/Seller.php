<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->role == 'Seller' && $user->status == 'accept') {
            return $next($request);
        }elseif($user->status != 'accept'){
            return response()->json([
                'status' => 'false',
                'text' => 'dear user please wait for admin acception'
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'text' => 'access denied'
            ]);
        }
    }
}
