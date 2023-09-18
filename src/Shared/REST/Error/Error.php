<?php

namespace App\Shared\REST\Error;

/**
 * Common API error.
 */
readonly class Error implements \JsonSerializable
{
    public function __construct(
        public string $message,
        public ?string $code = null,
        public ?string $path = null
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'code' => $this->code,
            'path' => $this->path,
        ];
    }
}
