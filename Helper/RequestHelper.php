<?php

namespace Gamboa\AdminBundle\Helper;

use Gamboa\AdminBundle\Annotation\PublicAccess;
use Gamboa\AdminBundle\Annotation\NotAuthenticated;
use Gamboa\AdminBundle\Annotation\Authenticated;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Doctrine\Common\Annotations\AnnotationReader;

class RequestHelper
{
    const AUTH_DEFAULT = -1;
    const AUTHENTICATED = 1;
    const NOT_AUTHENTICATED = 2;
    const PUBLIC_ACCESS = 3;

    private $request;

    function __construct(Request $req)
    {
        $this->request = $req;
    }

    function hasBearerToken() : bool
    {
        return $this->request->headers->has("Authorization");
    }

    function getBearerToken() : string
    {
        $token = null;
        if ($this->request->headers->has("Authorization")) {
            $token = str_replace("Bearer ", "", $this->request->headers->get("Authorization"));
        }
        return $token;
    }

    function typeOfAction() : int
    {
        $authenticatedAnnotation = null;
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->request);
        $reflectionMethod = new \ReflectionMethod($controller[0], $controller[1]);
        $reader = new AnnotationReader();
        // By default, all actions needs authentication
        $currentType = self::AUTH_DEFAULT;
        foreach ($reader->getMethodAnnotations($reflectionMethod) as $annotation) {
            if ($annotation instanceof Authenticated) {
                $currentType = self::AUTHENTICATED;
                break;
            }
            if ($annotation instanceof NotAuthenticated) {
                $currentType = self::NOT_AUTHENTICATED;
                break;
            }
            if ($annotation instanceof PublicAccess) {
                $currentType = self::PUBLIC_ACCESS;
                break;
            }
        }
        return $currentType;
    }

    function getAuthenticatedAnnotation() : array
    {
        $authenticatedAnnotation = null;
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->request);
        $reflectionMethod = new \ReflectionMethod($controller[0], $controller[1]);
        $reader = new AnnotationReader();
        foreach ($reader->getMethodAnnotations($reflectionMethod) as $annotation) {
            if ($annotation instanceof Authenticated) {
                $authenticatedAnnotation = $annotation;
                break;
            }
        }
        return $authenticatedAnnotation;
    }
}