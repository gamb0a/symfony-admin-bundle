<?php

namespace Gamboa\AdminBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Gamboa\AdminBundle\EventListener\ControllerListener;
use Gamboa\AdminBundle\Exception\ForbiddenHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Gamboa\AdminBundle\Helper\RequestHelper;
use Symfony\Component\HttpFoundation\ParameterBag;

class ControllerListenerTest extends TestCase
{
    public function testOnKernelControllerAccessDeniedNoAnnotation()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $requestHelper = $this->getRequestHelper();
        $requestHelper->expects($this->once())
            ->method('hasBearerToken')
            ->will($this->returnValue(false));

        $requestHelper->expects($this->once())
            ->method('getActionType')
            ->will($this->returnValue(RequestHelper::AUTH_DEFAULT));

        $listener = $this->getListenerMock($requestHelper);
        $listener->onKernelController($this->getControllerEventMockValidHost());
    }

    public function testOnKernelControllerAccessDeniedNotAuthenticatedUserAuthenticated()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $authService = $this->getAuthService();

        $userMock = $this->getMockBuilder('Gamboa\AdminBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $authService->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userMock));

        $requestHelper = $this->getRequestHelper();
        $requestHelper->expects($this->once())
            ->method('hasBearerToken')
            ->will($this->returnValue(true));

        $authService->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $requestHelper->expects($this->once())
            ->method('getActionType')
            ->will($this->returnValue(RequestHelper::NOT_AUTHENTICATED));

        $listener = $this->getListenerMock($requestHelper, $authService);
        $listener->onKernelController($this->getControllerEventMockValidHost());
    }

    public function testOnKernelControllerAccessGrantedAuthenticatedUser()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $authService = $this->getAuthService();

        $authService->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $userMock = $this->getMockBuilder('Gamboa\AdminBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $userMock->expects($this->once())
            ->method('hasAction')
            ->will($this->returnValue(false));

        $authService->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userMock));

        $requestHelper = $this->getRequestHelper();
        $requestHelper->expects($this->once())
            ->method('hasBearerToken')
            ->will($this->returnValue(true));

        $requestHelper->expects($this->once())
            ->method('getActionType')
            ->will($this->returnValue(RequestHelper::AUTHENTICATED));

        $listener = $this->getListenerMock($requestHelper, $authService);
        $listener->onKernelController($this->getControllerEventMockValidHost());
    }

    public function testOnKernelControllerAccessDeniedAuthenticatedUser()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $authService = $this->getAuthService();

        $authService->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $userMock = $this->getMockBuilder('Gamboa\AdminBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $userMock->expects($this->once())
            ->method('hasAction')
            ->will($this->returnValue(false));

        $authService->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($userMock));

        $requestHelper = $this->getRequestHelper();
        $requestHelper->expects($this->once())
            ->method('hasBearerToken')
            ->will($this->returnValue(true));

        $requestHelper->expects($this->once())
            ->method('getActionType')
            ->will($this->returnValue(RequestHelper::AUTHENTICATED));

        $listener = $this->getListenerMock($requestHelper, $authService);
        $listener->onKernelController($this->getControllerEventMockValidHost());
    }

    public function testOnKernelControllerAccessDeniedAuthenticatedAnnottaion()
    {
        $this->expectException(AccessDeniedHttpException::class);

        $requestHelper = $this->getRequestHelper();
        $requestHelper->expects($this->once())
            ->method('hasBearerToken')
            ->will($this->returnValue(false));

        $requestHelper->expects($this->once())
            ->method('getActionType')
            ->will($this->returnValue(RequestHelper::AUTHENTICATED));

        $listener = $this->getListenerMock($requestHelper);
        $listener->onKernelController($this->getControllerEventMockValidHost());
    }

    public function testOnKernelControllerInvalidHost()
    {
        $requestHelper = $this->getRequestHelper();
        $this->expectException(ForbiddenHttpException::class);
        $listener = $this->getListenerMock($requestHelper);
        $listener->onKernelController($this->getControllerEventMockInvalidHost());
    }

    public function getListenerMock($reqHelper, $authService = null)
    {
        $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        if (null == $authService) {
            $authService = $this->getMockBuilder('Gamboa\AdminBundle\Service\AuthService')
                ->disableOriginalConstructor()
                ->getMock();
        }

        $listener = new ControllerListener($logger, $authService, $reqHelper);

        return $listener;
    }

    public function getAuthService()
    {
        $reqHelper = $this->getMockBuilder('Gamboa\AdminBundle\Service\AuthService')
            ->disableOriginalConstructor()
            ->getMock();

        return $reqHelper;
    }

    public function getRequestHelper()
    {
        $reqHelper = $this->getMockBuilder('Gamboa\AdminBundle\Helper\RequestHelper')
            ->disableOriginalConstructor()
            ->getMock();

        return $reqHelper;
    }

    public function getControllerEventMockInvalidHost()
    {
        $filterControllerEvent = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterControllerEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('getHost')
            ->will($this->returnValue('127.0.0.1'));

        $filterControllerEvent->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        return $filterControllerEvent;
    }

    public function getControllerEventMockValidHost()
    {
        $filterControllerEvent = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterControllerEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('getHost')
            ->will($this->returnValue('localhost'));

        $request->attributes = new ParameterBag();

        $filterControllerEvent->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($request));

        return $filterControllerEvent;
    }
}
