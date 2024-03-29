<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ErrorDetails
{
    public string $instance;
    public string $type;
    /**
     * @param mixed $type
     * @param mixed $title
     * @param mixed $detail
     * @param mixed $instance
     * @param mixed $
     */
    public function __construct(
        string $type,
        public string $title,
        public int $status,
        public string|array $detail,
        Request $request
    ) {
        $this->instance = $request->getUri();
        $this->type = $request->getBaseUrl() . "/problems/" . $type;
    }

    public function emit(): JsonResponse
    {
        return response()->json($this, $this->status, [
            "Content-Type" => "application/problem+json",
        ]);
    }
}
