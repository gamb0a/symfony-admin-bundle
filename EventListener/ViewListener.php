<?php

namespace Gamboa\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewListener
{
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        if (!is_array($result)) {
            throw new \UnexpectedValueException('La respuesta debe ser de tipo array');
        }
        $event->setResponse(new JsonResponse($result));
    }
}
