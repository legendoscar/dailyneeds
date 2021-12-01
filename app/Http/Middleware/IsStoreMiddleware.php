<?php

namespace App\Http\Middleware;

use Closure;

class IsStoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'store')
    {

        if (!auth()->guard($guard)->check()) {
            // return redirect('/');
            return response()->json([
                'msg' => 'Forbidden! You don\'t have store access', 
                'errCode' => 403 
            ]);
        }

        return $next($request);

    }
}
