<?php

namespace Gamboa\AdminBundle\EventListener;

use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = new JsonResponse();
        $payload = ["message" => $exception->getMessage(), "code" => $statusCode];

        if ($exception instanceof BadRequestHttpException) {
            $payload["errors"] = $exception->getErrorList();
        }

        if ($_ENV['APP_ENV'] === 'dev')
            $payload["trace"] = $exception->getTraceAsString();

        $response->setData($payload);
        $event->setResponse($response);
    }
}