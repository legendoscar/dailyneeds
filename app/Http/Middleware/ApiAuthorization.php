<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Access\AuthorizationException;

class ApiAuthorization extends Authorize
{
    public function handle($request, Closure $next, $ability, ...$models)
    {
        try {
            $this->auth->authenticate();

            $this->gate->authorize($ability, $this->getGateArguments($request, $models));
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        return $next($request);
    }
}