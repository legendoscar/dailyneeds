<?php

namespace App\Http\Middleware;

use Closure;

class IsCustomerMiddleware
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

        if(auth()->user() && auth()->user()->user_role == 3){
            return $next($request);
        }

        return response()->json([
            'msg' => 'Forbidden! You don\'t have a customer account.', 
            'errCode' => 403 
        ]);
    }
}
