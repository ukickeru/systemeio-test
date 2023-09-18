<?php

namespace App\Shared\REST;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'formatResponseOnKernelException')]
class ExceptionFormattingListener
{
    public function __construct(private readonly ExceptionHandler $errorHandler)
    {
    }

    public function formatResponseOnKernelException(ExceptionEvent $event): void
    {
        $handledResponse = $this->errorHandler->createResponseFromException($event->getThrowable());
        $event->setResponse($handledResponse);
    }
}
