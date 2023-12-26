<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Shareds\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use TypeError;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TypeError) {
            return ApiResponser::errorResponse('Internal Server Error', 500);
        }
        if ($exception instanceof HttpException) {
            return ApiResponser::errorResponse($exception->getMessage());
        }
        if ($exception instanceof ValidationException) {
            return ApiResponser::errorResponse('Invalid data submitted', 422, $exception->errors());
        }
        if ($exception instanceof AuthenticationException) {
            return ApiResponser::errorResponse($exception->getMessage());
        }
        if ($exception instanceof ModelNotFoundException) {
            return ApiResponser::errorResponse($exception->getMessage());
        }
        if ($exception instanceof QueryException) {
            return ApiResponser::errorResponse($exception->getMessage());
        }
        return parent::render($request, $exception);
    }
}
