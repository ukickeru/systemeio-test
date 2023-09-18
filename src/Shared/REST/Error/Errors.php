<?php

namespace App\Shared\REST\Error;

/**
 * JSON API-specific errors container.
 */
class Errors implements \JsonSerializable
{
    /**
     * @param Error[] $errors
     */
    public function __construct(private array $errors)
    {
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(Error $error): void
    {
        $this->errors[] = $error;
    }

    public function jsonSerialize(): array
    {
        return ['errors' => $this->errors];
    }
}
