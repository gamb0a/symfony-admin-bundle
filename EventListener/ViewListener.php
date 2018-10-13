<?php 

namespace Gamboa\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use \Symfony\Component\HttpFoundation\JsonResponse;

class ViewListener
{
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $event->setResponse(new JsonResponse($result));
    }
}