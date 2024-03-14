<?php

namespace App\Exceptions;

class ErrorDetails
{
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
            public string | array $detail,
            public string $instance)
    {
    }
}
