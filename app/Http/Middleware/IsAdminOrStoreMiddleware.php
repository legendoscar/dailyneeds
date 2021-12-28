<?php

namespace App\Http\Middleware;

use Closure;

class IsAdminOrStoreMiddleware
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

        if(auth()->user()->user_role == 1 || auth()->guard('store')->user()){
            
            // return 33;
            return $next($request);
        }

        return response()->json([
            'msg' => 'Forbidden! You neither an admin nor a store', 
            'errCode' => 403 
        ]);
    }
}
