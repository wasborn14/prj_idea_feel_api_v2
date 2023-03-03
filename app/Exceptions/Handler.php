<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse
     * @throws Exception
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        $code = null;

        switch (get_class($exception)) {
            case BadRequestException::class:
                $code = 400;
                break;
            case UnauthorizedException::class:
                $code = 401;
                break;
            case ForbiddenException::class:
                $code = 403;
                break;
            case NotFoundException::class:
                $code = 404;
                break;
            case InternalServerErrorException::class:
                $code = 500;
                break;
            case MethodNotAllowedHttpException::class:
            case NotFoundHttpException::class:
                return response()->json([
                    'code' => 404,
                    'message' => 'route not found',
                ], 404);
            case ServiceUnavailableException::class:
                $code = 503;
                break;
            default:
        }

        if ($code) {
            return response()->json([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'tip' => $exception->getTip(),
            ], $code);
        }

        Log::debug('class', [$exception]);
        Log::debug('exception', [$exception->getMessage()]);
        
        if ($exception->getMessage() === "Your email address is not verified.") {
            return response()->json([
                    'errMsg'   => '認証エラーが発生しました。再度ログインしなおしてください。'
                ], 401);
        }

        Log::error(__FILE__ . ':' . __LINE__ . ' 適切なエラーを実装してください(' . $exception->getMessage() . ')');
        Log::error($exception->getTraceAsString());

        return response()->json([
            'code' => 500,
            'message' => 'Internal Server Error',
            'tip' => '予期しないエラーが発生しました',
        ], 500);
    }
}
