<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;
use Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // In Laravel 5.7 or above route checking happens before exception throwing, so unauthenticate override exception does not apply
        // and if application/json is not used it will send to route::login with a 500 errors because it does not exist
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}
