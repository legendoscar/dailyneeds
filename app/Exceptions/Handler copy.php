<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
// use Illuminate\Auth\Access\AuthorizationException;
use Throwable, Exception, Auth;

use Response;
// use Exception;
// use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

// use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * This mapping holds exceptions we're interested in and creates a simple configuration that can guide us
     * with formatting how it is rendered.
     *
     * @var array|array[]
     */
    protected array $exceptionMap = [
        ModelNotFoundException::class => [
            'code' => 404,
            'message' => 'Could not find what you were looking for.',
            'adaptMessage' => false,
        ],
        
        NotFoundHttpException::class => [
            'code' => 404,
            'message' => 'Could not find what you were looking for.',
            'adaptMessage' => false,
        ],
        
        MethodNotAllowedHttpException::class => [
            'code' => 405,
            'message' => 'This method is not allowed for this endpoint.',
            'adaptMessage' => false,
        ],
        
        ValidationException::class => [
            'code' => 422,
            'message' => 'Some data failed validation in the request',
            'adaptMessage' => false,
        ],
        
        \InvalidArgumentException::class => [
            'code' => 400,
            'message' => 'You provided some invalid input value',
            'adaptMessage' => true,
        ],
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    { 
        $response = $this->formatException($exception);
    
        return response()->json(['error' => $response], $response['status'] ?? 500);

        return parent::render($request, $exception);
    }


    /**
     * A simple implementation to help us format an exception before we render me
     *
     * @param \Throwable $exception
     *
     * @return array
     */
    protected function formatException(\Throwable $exception): array
    {
        # We get the class name for the exception that was raised
        $exceptionClass = get_class($exception);
    
        # we see if we have registered it in the mapping - if it isn't
        # we create an initial structure as an 'Internal Server Error'
        # note that this can always be revised at a later time
        $definition = $this->exceptionMap[$exceptionClass] ?? [
            'code' => 500,
            'message' => $exception->getMessage() ?? 'Something went wrong while processing your request',
            'adaptMessage' => false,
        ];
    
        if (! empty($definition['adaptMessage'])) {
        
            $definition['message'] = $exception->getMessage() ?? $definition['message'];
        
        }
        
        return [
            'status' => $definition['code'] ?? 500,
            'title' => $definition['title'] ?? 'Error',
            'description' => $definition['message'],
        ];
    }



    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/login/admin');
        }
        if ($request->is('customer') || $request->is('customer/*')) {
            return redirect()->guest('/login/customer');
        }
        return redirect()->guest(route('login'));
    }

     /**
     * Check the validity of token.
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
    
    public function register()
    {

        $this->renderable(function(TokenInvalidException $e, $request){
                return Response::json(['error'=>'Invalid token'],401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return Response::json(['error'=>'Token has Expired'],401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return Response::json(['error'=>'Token not parsed'],401);
        });

    }
}
