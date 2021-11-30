<?php

namespace App\Http\Middleware;

use Closure;

use Tymon\JWTAuth as JWTAuth;

class JWTMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken($request->header("Authorization"))->authenticate();
        $user = JWTAuth::toUser($user);
        Auth::login($user);
        
        return $next($request);
    }
}
