<?php

namespace Gamboa\AdminBundle\Helper;

use Gamboa\AdminBundle\Annotation\PublicAccess;
use Gamboa\AdminBundle\Annotation\NotAuthenticated;
use Gamboa\AdminBundle\Annotation\Authenticated;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Doctrine\Common\Annotations\AnnotationReader;

class RequestHelper {
    
    const AUTHENTICATED = 1;
    const NOT_AUTHENTICATED = 2;
    const PUBLIC_ACCESS = 2;

    private $request;

    function __construct(Request $req) {
        $this->request = $req;
    }

    function typeOfAction() {
        $authenticatedAnnotation = null;
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->request);
        $reflectionMethod = new \ReflectionMethod($controller[0], $controller[1]);
        $reader  = new AnnotationReader();
        foreach($reader->getMethodAnnotations($reflectionMethod) as $annotation) {
            if ($annotation instanceof Authenticated) return self::AUTHENTICATED;
            if ($annotation instanceof NotAuthenticated) return self::NOT_AUTHENTICATED;
            if ($annotation instanceof PublicAccess) return self::PUBLIC_ACCESS;
        }
        // By default, all actions needs authentication
        return self::AUTHENTICATED;
    }

    function getAutenticatedAnnotation() {
        $authenticatedAnnotation = null;
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->request);
        $reflectionMethod = new \ReflectionMethod($controller[0], $controller[1]);
        $reader  = new AnnotationReader();
        foreach($reader->getMethodAnnotations($reflectionMethod) as $annotation) {
            if ($annotation instanceof Authenticated) {
                $authenticatedAnnotation = $annotation->getAsArray();
                break;
            }
        }
        return $authenticatedAnnotation;
    }
}