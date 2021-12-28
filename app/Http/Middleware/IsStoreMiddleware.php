<?php

namespace App\Http\Middleware;

use Closure;

class IsStoreMiddleware
{

    public function before($request, Closure $next){
        if(auth()->user()->user_role === 1 ){
            return $next($request);
        }
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
// return 33;
        if (auth()->guard('store')->user()) {
            // return redirect('/');
            return $next($request);
        }
        return response()->json([
            'msg' => 'Forbidden! You don\'t have store access', 
            'errCode' => 403 
        ]); 


    }
}
