<?php

namespace Gamboa\AdminBundle\EventListener;

use Gamboa\AdminBundle\Helper\RequestHelper;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ControllerListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        // Hostname validation
        $host = $request->getHost();
        if (!in_array($host, explode(",", $_ENV["TRUSTED_HOSTS"]))) {
            throw new ForbiddenHttpException();
        }

        $requestHelper = new RequestHelper($request);
        $typeOfAction = $requestHelper->typeOfAction();
        
        if ($typeOfAction === RequestHelper::PUBLIC_ACCESS) {
            return;
        }

        // Bearertoken validation
        $bearerToken = null;
        if ($request->headers->has("Authorization")) {
            $bearerToken = $request->headers->get("Authorization");
        }
    }
}