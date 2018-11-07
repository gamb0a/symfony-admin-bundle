<?php

namespace Gamboa\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Gamboa\AdminBundle\Exception\ForbiddenHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
use Gamboa\AdminBundle\Service\AuthService;
use Gamboa\AdminBundle\Helper\RequestHelper;

class ControllerListener
{
    private $requestHelper;
    private $logger;
    private $authService;

    public function __construct(LoggerInterface $logger, AuthService $authService, RequestHelper $requestHelper)
    {
        $this->logger = $logger;
        $this->authService = $authService;
        $this->requestHelper = $requestHelper;
    }

    private function validateHostname(string $host) {
        if (isset($_ENV["TRUSTED_HOSTS"])) {
            if (!in_array($host, explode(",", $_ENV["TRUSTED_HOSTS"]))) {
                throw new ForbiddenHttpException();
            }
        } else {
            $this->logger->warn('No env variable "TRUSTED_HOST"');
        }
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $this->requestHelper->setRequest($request);
        

        // Hostname validation
        $this->validateHostname($request->getHost());

        $actionType = $this->requestHelper->getActionType();

        $authenticatedUser = null;
        $bearerToken = null;
        if ($this->requestHelper->hasBearerToken()) {
            $bearerToken = $this->requestHelper->getBearerToken();
            if ($this->authService->isValid($bearerToken))
                $authenticatedUser = $this->authService->getUser($bearerToken);
        }
        
        $this->requestHelper->setUser($authenticatedUser);
        // public access, no checks
        if ($actionType === RequestHelper::PUBLIC_ACCESS) {
            return;
        }
        
        // AuthenticatedAction
        if ($actionType === RequestHelper::AUTHENTICATED) {
            
            if ($authenticatedUser == null) {
                $this->logger->error("UnauthenticatedUser trying to execute AuthenticatedAction");
                throw new AccessDeniedHttpException("No tiene permiso para acceder a esta sección");
            }

            $currentActionAnnotation = $this->requestHelper->getAuthenticatedAnnotation();
            // Authenticated user actions validation
            if (!$authenticatedUser->hasAction($currentActionAnnotation)) {
                $this->logger->error("AuthenticatedUser trying to execute Unauthorized Action");
                throw new AccessDeniedHttpException("No tiene permiso para acceder a esta sección");
            }

        } elseif ($actionType === RequestHelper::NOT_AUTHENTICATED) {
            if ($authenticatedUser !== null) {
                $this->logger->error("AuthenticatedUser trying to execute NotAuthenticatedAction");
                throw new AccessDeniedHttpException("No tiene permiso para acceder a esta sección");
            }
        } else {
            // By default its an authenticated action
            if ($authenticatedUser == null) {
                $this->logger->error("NonAuthenticated User trying to execute AuthenticatedAction");
                throw new AccessDeniedHttpException("No tiene permiso para acceder a esta sección");
            }
        }
    }
}