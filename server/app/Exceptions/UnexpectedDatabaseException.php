<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnexpectedDatabaseException extends Exception
{
    public function render(Request $r): Response
    {
        return response()->json(
            new ErrorDetails(
                instance: $r->getUri(),
                type: $r->getBaseUrl() . "/probs/erro-acesso",
                detail: "Não foi possível realizar operação! Contate o suporte!",
                status: 500,
                title: "Erro de Acesso"
            ),
            500,
            [
                "Content-Type" => "application/problem+json",
            ]
        );
    }
}
