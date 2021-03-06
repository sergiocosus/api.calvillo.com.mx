<?php

namespace CalvilloComMx\Exceptions;

use CalvilloComMx\Core\Category;
use CalvilloComMx\Http\Response;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Lang;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
       // \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $message = 'Sorry, something went wrong.';

        if (config('app.debug')) {
            $message = $exception->getMessage();
            $trace = $exception->getTrace();
        }

        $status = 400;

        if ($this->isHttpException($exception)) {
            $message = \Request::getUri();
            $status = $exception->getStatusCode();
        }

        if ($exception instanceof ValidationException) {
            $message = implode(',', $exception->validator->errors()->all());
        }

        if ($exception instanceof ModelNotFoundException) {
            switch($exception->getModel()){
                case Category::class:
                    return Response::error(1001, Lang::get('category.not_found'));
            }
        }

        return Response::error($status, $message, compact('trace'));

        //return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
