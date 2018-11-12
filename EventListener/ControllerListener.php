<?php

namespace Gamboa\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
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

    private function validateHostname(string $host)
    {
        if (isset($_ENV['TRUSTED_HOSTS'])) {
            if (!in_array($host, explode(',', $_ENV['TRUSTED_HOSTS']))) {
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
            if ($this->authService->isValid($bearerToken)) {
                $authenticatedUser = $this->authService->getUser($bearerToken);
            }
        }

        $this->requestHelper->setUser($authenticatedUser);
        // public access, no checks
        if (RequestHelper::PUBLIC_ACCESS === $actionType) {
            return;
        }

        // AuthenticatedAction
        if (RequestHelper::AUTHENTICATED === $actionType) {
            if (null == $authenticatedUser) {
                $this->logger->error('UnauthenticatedUser trying to execute AuthenticatedAction');
                throw new AccessDeniedHttpException('No tiene permiso para acceder a esta sección');
            }

            $currentAuthenticatedActionName = $this->requestHelper->getAuthenticatedActionName();
            // Authenticated user actions validation
            if (!$authenticatedUser->hasAction($currentAuthenticatedActionName)) {
                $this->logger->error('AuthenticatedUser trying to execute Unauthorized Action');
                throw new AccessDeniedHttpException('No tiene permiso para acceder a esta sección');
            }
        } elseif (RequestHelper::NOT_AUTHENTICATED === $actionType) {
            if (null !== $authenticatedUser) {
                $this->logger->error('AuthenticatedUser trying to execute NotAuthenticatedAction');
                throw new AccessDeniedHttpException('No tiene permiso para acceder a esta sección');
            }
        } else {
            // By default its an authenticated action
            if (null == $authenticatedUser) {
                $this->logger->error('NonAuthenticated User trying to execute AuthenticatedAction');
                throw new AccessDeniedHttpException('No tiene permiso para acceder a esta sección');
            }
        }
    }
}
