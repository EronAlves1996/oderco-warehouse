<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ErrorDetails
{
    public string $instance;
    /**
     * @param mixed $type
     * @param mixed $title
     * @param mixed $detail
     * @param mixed $instance
     * @param mixed $
     */
    public function __construct(
        public string $type,
        public string $title,
        public int $status,
        public string|array $detail,
        public Request $request
    ) {
        $this->instance = $request->getUri();
    }

    public function emit(): Response
    {
        return response()->json($this, $this->status, [
            "Content-Type" => "application/problem+json",
        ]);
    }
}
