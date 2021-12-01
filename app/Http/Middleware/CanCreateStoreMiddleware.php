<?php

namespace App\Http\Middleware;

use Closure;

class CanCreateStoreMiddleware
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

        if(auth()->user() && (auth()->user()->user_role == 1 || auth()->user()->user_role == 2)){
            // return 1;
            return $next($request);

        }elseif(auth()->user() && !auth()->user()->user_role == 1){
           
            // return 2;
            return response()->json([
                'msg' => 'Sorry! Only admins are permitted to create stores while logged in.', 
                'errCode' => 403 
            ]);
        }
            return $next($request);
    }
}

