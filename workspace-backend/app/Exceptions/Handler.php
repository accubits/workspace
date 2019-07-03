<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['error' => "The specified url cannot be found",
                'code' => 404], 404
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
            return response()->json(['error' => "Does not exists any {$modelName} with the specific identification", 'code' => 404], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['error' => "Unauthenticated", 'code' => 401], 401);
            //$this->unauthenticated($request, $exception);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'The specified method for the request is invalid',
                'code' => 404], 404
            );
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->json(['error' => $exception->getMessage(),
                'code' => 403], 403
            );
        }

        if ($exception instanceof ValidationException) {
            $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof HttpException) {
            return response()->json($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return response()->json(["error" => "Cannot remove resource permanently . It is related with other resource",
                    "code" => 409], 409);
            }
            dd($exception);
        }

        if ($exception instanceof \InvalidArgumentException) {
            return response()->json(["error" => "Invalid arguments for route", "code" => 404], 404);
        }

        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        $errors = collect($errors);
        $arr    = array();
        $errorsArr = $errors->map(function ($item, $key) use(&$arr) {
            $errors[$key] = $item[0];
            array_push($arr,$item[0]);
            return $errors[$key];
        });

        $resp = $errorsArr->toArray();

        $resp['msg'] = implode($arr,' , ');
        return response()->json(['error' => $resp, 'code' => 422], 422);
    }
}
