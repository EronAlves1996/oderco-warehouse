<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnexpectedDatabaseException extends Exception
{
    public function render(Request $r): JsonResponse
    {
        return (new ErrorDetails(
            request: $r,
            type: "erro-acesso",
            detail: "Não foi possível realizar operação! Contate o suporte!",
            status: 500,
            title: "Erro de Acesso"
        ))->emit();
    }
}
