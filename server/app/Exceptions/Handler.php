<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
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
                type: "unprocessable-entity",
                title: "Requisição inválida",
                detail: $e->errors(),
                status: 422,
                request: $r
            ))->emit()
        );

        $this->renderable(
            fn(NotFoundHttpException $e, Request $r) => (new ErrorDetails(
                type: "nao-encontrado",
                title: "Não encontrado",
                detail: "Entidade ou URL não encontrada ou não existe",
                status: 404,
                request: $r
            ))->emit()
        );

        $this->renderable(
            fn(QueryException $e, Request $r) => (new ErrorDetails(
                type: "erro-de-banco",
                title: "Erro de Banco de Dados",
                detail: "Não foi possível conectar ao banco",
                status: 500,
                request: $r
            ))->emit()
        );
    }
}
