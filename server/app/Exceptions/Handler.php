<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation",
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(
            fn(ValidationException $e, Request $r) => (new ErrorDetails(
                type: $r->getBaseUrl() . "/problems/unprocessable-entity",
                title: "Requisição inválida",
                detail: $e->errors(),
                status: 422,
                request: $r
            ))->emit()
        );

        $this->renderable(
            fn(NotFoundHttpException $e, Request $r) => (new ErrorDetails(
                type: $r->getBaseUrl() . "/problems/nao-encontrado",
                title: "Não encontrado",
                detail: "Entidade ou URL não encontrada ou não existe",
                status: 404,
                request: $r
            ))->emit()
        );
    }
}
