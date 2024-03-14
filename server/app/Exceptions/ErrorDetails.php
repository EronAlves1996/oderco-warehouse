<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        Request $request
    ) {
        $this->instance = $request->getUri();
    }

    public function emit(): JsonResponse
    {
        return response()->json($this, $this->status, [
            "Content-Type" => "application/problem+json",
        ]);
    }
}
