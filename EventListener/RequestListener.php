<?php

namespace Gamboa\AdminBundle\EventListener;

use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) return;

        $request = $event->getRequest();

        $bearerToken = null;
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->headers->has("Authorization");
        } else {
            echo "No posee token";
        }
        exit;
    }
}