<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ForbiddenOperationException extends Exception
{
    public function __construct(private string $reason)
    {
    }

    public function render(Request $r): Response
    {
        return (new ErrorDetails(
            request: $r,
            type: $r->getBaseUrl() . "/probs/operacao-proibida",
            detail: $this->reason,
            status: 403,
            title: "Operação proibida"
        ))->emit();
    }
}
