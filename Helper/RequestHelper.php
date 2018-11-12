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

    public function setRequest(Request $req)
    {
        $this->request = $req;
    }

    public function hasBearerToken(): bool
    {
        return $this->request->headers->has('Authorization');
    }

    public function getBearerToken(): string
    {
        if ($this->request->headers->has('Authorization')) {
            $token = trim(str_replace('Bearer', '', $this->request->headers->get('Authorization')));
            if ('' === $token) {
                throw new \RuntimeException('Authorization header inválido');
            }
        } else {
            throw new \RuntimeException('No posee header Authorization');
        }

        return $token;
    }

    private function getAnnotations()
    {
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->getController($this->request);
        $reflectionMethod = new \ReflectionMethod($controller[0], $controller[1]);
        $reader = new AnnotationReader();

        return $reader->getMethodAnnotations($reflectionMethod);
    }

    public function getActionType(): int
    {
        // By default, all actions needs authentication
        $currentType = self::AUTH_DEFAULT;
        foreach ($this->getAnnotations() as $annotation) {
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

    public function getAuthenticatedActionName(): string
    {
        $authenticatedAnnotation = null;
        foreach ($this->getAnnotations() as $annotation) {
            if ($annotation instanceof Authenticated) {
                $authenticatedAnnotation = $annotation;
                break;
            }
        }

        if (null === $authenticatedAnnotation) {
            throw new \RuntimeException('Authenticated Annotation Not Found');
        }

        return $authenticatedAnnotation->getName();
    }

    public function getUser($user)
    {
        $this->request->attributes->get('user');
    }

    public function setUser($user)
    {
        $this->request->attributes->set('user', $user);
    }
}
