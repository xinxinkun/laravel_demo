<?php

namespace App\Http\Exceptions;

use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected array $exceptionBasicCode = [
        AuthException::class => 3000
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        AuthException::class
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
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return ApiResponse
     */
    public function render($request, Exception $exception)
    {
        $apiCode = array_get($this->exceptionBasicCode, get_class($exception));
        Log::info($exception->getMessage());
        if ($apiCode) {
            $apiCode += $exception->getCode();
            $message = config('api_code.' . $apiCode, $exception->getMessage());
            $data = null;
            if($exception instanceof BasicException) {
                $data = $exception->getData();
            }
            return new ApiResponse($data, $apiCode, $message);
        }

        if (!env('APP_DEBUG') || $request->expectsJson()) {
            return new ApiResponse(
                null,
                'UNKNOWN-' . $exception->getCode(),
                env('APP_DEBUG') || $exception instanceof \InvalidArgumentException ? $exception->getMessage() : 'Unknown error',
                $this->isHttpException($exception) ? $exception->getStatusCode() : 200,
                $this->isHttpException($exception) ? $exception->getHeaders() : []
            );
        }

        return parent::render($request, $exception);
    }
}
