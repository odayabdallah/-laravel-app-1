<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class APIAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token=$request->headers('access_token');
        if(!$access_token){
            return response()->json([
                'message'=>'Access token is missing',
            ],401);
        }
        $user=User::where('access token ',$access_token)->first();
        if(!$user){
            return response()->json([
                'message'=>' access token is invaled',
            ],401);
        }
        return $next($request);
    }
}
