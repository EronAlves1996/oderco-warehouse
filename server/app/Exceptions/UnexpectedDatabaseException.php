<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnexpectedDatabaseException extends Exception
{
    public function render(Request $r): Response
    {
        return (new ErrorDetails(
            request: $r,
            type: $r->getBaseUrl() . "/probs/erro-acesso",
            detail: "Não foi possível realizar operação! Contate o suporte!",
            status: 500,
            title: "Erro de Acesso"
        ))->emit();
    }
}
