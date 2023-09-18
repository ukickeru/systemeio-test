<?php

namespace App\Shared\Logger;

class ExceptionHelper
{
    /**
     * @return array{class: string, message: string, code: int}
     */
    public static function serializeMainData(\Throwable $exception): array
    {
        return [
            'class' => $exception::class,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];
    }
}
