<?php

namespace App\Shared\REST;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler
{
    private const DEFAULT_ERROR_STATUS_CODE = 400;

    public function __construct(private readonly ExceptionConverter $exceptionConverter)
    {
    }

    /**
     * Method converts the response with an error to the specified format (JSON with errors data and 400 status code).
     *
     * WARNING: method should not change the structure of the response so crudely.
     * Response formatters/converters MUST be set for different groups/types of controllers explicitly (manually)
     * or MUST be based on the analysis of many features - request entry point, API and content type, etc.
     */
    public function createResponseFromException(\Throwable $exception): Response
    {
        return new JsonResponse($this->exceptionConverter->convert($exception), self::DEFAULT_ERROR_STATUS_CODE);
    }
}
