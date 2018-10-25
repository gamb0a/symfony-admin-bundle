<?php

namespace Gamboa\AdminBundle\EventListener;

use Gamboa\AdminBundle\Helper\RequestHelper;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Gamboa\AdminBundle\Service\AuthService;

class ControllerListener
{

    private $logger;
    private $authService;

    public function __construct(LoggerInterface $logger, AuthService $authService)
    {
        $this->logger = $logger;
        $this->authService = $authService;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        // Hostname validation
        $host = $request->getHost();
        if (isset($_ENV["TRUSTED_HOSTS"])) {
            if (!in_array($host, explode(",", $_ENV["TRUSTED_HOSTS"]))) {
                throw new ForbiddenHttpException();
            }
        } else
            $this->logger->warn('No env variable "TRUSTED_HOST"');

        $requestHelper = new RequestHelper($request);
        $typeOfAction = $requestHelper->typeOfAction();

        // if public access, continue
        if ($typeOfAction === RequestHelper::PUBLIC_ACCESS) {
            return;
        }
        $authenticatedUser = null;
        $bearerToken = null;
        if ($requestHelper->hasBearerToken()) {
            $bearerToken = $requestHelper->getBearerToken();
            if ($this->authService->isValid($bearerToken))
                $authenticatedUser = $this->authService->getUser($bearerToken);
        }
        
        // if its an AuthenticatedAction, we need to validate
        // the current session and its actions
        if ($typeOfAction === RequestHelper::AUTHENTICATED) {
            $currentActionAnnotation = $requestHelper->getAuthenticatedAnnotation();
            if ($authenticatedUser == null) {
                $this->logger->error("UnatuhenticatedUser trying to execute AuthenticatedAction");
                throw new AccessDeniedHttpException("No tiene permiso para acceder a esta sección");
            }

            $request->attributtes->set("user", $authenticatedUser);
            // Authenticated user actions validation
            if (!in_array($currentActionAnnotation->getName(), $authenticatedUser->get("actions"))) {
                $this->logger->error("AuthenticatedUser trying to execute Unauthorized Action");
                throw new AccessDeniedHttpException("No tiene permiso para acceder a esta sección");
            }

        } elseif ($typeOfAction === RequestHelper::NOT_AUTHENTICATED) {
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
            $request->attributtes->set("user", $authenticatedUser);
        }
    }
}