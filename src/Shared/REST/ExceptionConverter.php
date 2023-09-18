<?php

namespace App\Shared\REST;

use App\Shared\REST\Error\Error;
use App\Shared\REST\Error\Errors;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionConverter
{
    /**
     * Implemented as a simple method to avoid creating abstraction for this small task.
     * Later implementation may be improved behind the convert(\Throwable $exception) interface.
     */
    public function convert(\Throwable $exception): Errors
    {
        $errors = [];

        if ($exception instanceof ValidationFailedException) {
            $errors = $this->convertViolationsList($exception->getViolations());
        } elseif ($exception->getPrevious() instanceof ValidationFailedException) {
            $errors = $this->convertViolationsList($exception->getPrevious()->getViolations());
        } else {
            $errors[] = new Error($exception->getMessage(), $exception->getCode());
        }

        return new Errors($errors);
    }

    /**
     * @return Error[]
     */
    private function convertViolationsList(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];

        foreach ($violationList as $violation) {
            $errors[] = new Error($violation->getMessage(), $violation->getCode(), $violation->getPropertyPath());
        }

        return $errors;
    }
}
