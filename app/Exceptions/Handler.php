<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
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
            return response()->json(['401_error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    public function render($request, Exception $e)
    {
        if ($e instanceof TokenMismatchException) {
            return redirect()->back()->withErrors(['error' => 'Diese Seite ist abgelaufen. Bitte versuche es erneut']);
        }
        return parent::render($request, $e);
    }

    public function renderHttpException(HttpException $e)
    {
        return response()->view('errors.error', ['exception' => $e], $e->getStatusCode());
    }
}
