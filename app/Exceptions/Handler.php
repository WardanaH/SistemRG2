<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed on validation exceptions.
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

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // 403 - Permission Denied (Spatie)
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->view('errors.403', [], 403);
        }

        // 404 - Halaman tidak ditemukan
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        // ⚠️ Jika APP_DEBUG = true → gunakan debug bawaan Laravel (Whoops)
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        // 🔥 Jika APP_DEBUG = false → tampilkan custom 500 page
        return response()->view('errors.500', ['error' => $exception->getMessage()], 500);
    }
}
