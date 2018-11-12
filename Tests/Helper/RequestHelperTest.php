<?php

namespace Gamboa\AdminBundle\Tests\Request;

use Gamboa\AdminBundle\Helper\RequestHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Gamboa\AdminBundle\Annotation\NotAuthenticated;
use Gamboa\AdminBundle\Annotation\Authenticated;
use Gamboa\AdminBundle\Annotation\PublicAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestHelperTest extends TestCase
{

    /**
     * @var string $actionName
     * 
     * @dataProvider invalidAuthenticatedActionProvider
     */
    public function testThrowExceptionGetAnnotationName(string $actionName)
    {
        $helper = new RequestHelper();
        $object = new ControllerTest();
        $request = Request::create('/');
        $request->attributes->set('_controller', array($object, $actionName));
        $helper->setRequest($request);
        $this->expectException(\RuntimeException::class);
        $helper->getAuthenticatedActionName();
    }

    public function testGetAnnotationName()
    {
        $helper = new RequestHelper();
        $object = new ControllerTest();
        $request = Request::create('/');
        $request->attributes->set('_controller', array($object, 'authenticatedAction'));
        $helper->setRequest($request);
        $this->assertEquals('test name', $helper->getAuthenticatedActionName());
    }

    /**
     * @var string $actionName
     * @var int $actionType
     * 
     * @dataProvider actionProvider
     */
    public function testGetActionType(string $actionName, int $actionType)
    {
        $helper = new RequestHelper();
        $object = new ControllerTest();
        $request = Request::create('/');
        $request->attributes->set('_controller', array($object, $actionName));
        $helper->setRequest($request);
        $this->assertEquals($actionType, $helper->getActionType());
    }

    /**
     * @var array $headersArray
     * @var bool $trueOrFalse
     * 
     * @dataProvider headerProvider
     */
    public function testHasAndGetToken(array $headersArray, bool $trueOrFalse, $expectedValue)
    {
        $helper = new RequestHelper();
        $helper->setRequest($this->getRequest($headersArray));
        $this->assertEquals($helper->hasBearerToken(), $trueOrFalse);
        
        if ($expectedValue === null) {
            $this->expectException(\RuntimeException::class);
            $token = $helper->getBearerToken();
        } else {
            $this->assertEquals($helper->getBearerToken(), $expectedValue);
        }
    }

    public function getRequest(array $headersArray) {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $headers = new ParameterBag($headersArray);
        $request->headers = $headers;

        return $request;
    }

    public function actionProvider() {
        return [
            ['authenticatedDefaultAction', RequestHelper::AUTH_DEFAULT],
            ['notAuthenticatedAction', RequestHelper::NOT_AUTHENTICATED],
            ['authenticatedAction', RequestHelper::AUTHENTICATED],
            ['publicAction', RequestHelper::PUBLIC_ACCESS],
        ];
    }

    public function invalidAuthenticatedActionProvider() {
        return [
            ['authenticatedDefaultAction', RequestHelper::AUTH_DEFAULT],
            ['notAuthenticatedAction', RequestHelper::NOT_AUTHENTICATED],
            ['publicAction', RequestHelper::PUBLIC_ACCESS],
        ];
    }

    public function headerProvider() {
        return [
            [["Authorization" => ""], true, null],
            [["Authorization" => "Bearer example"], true, 'example'],
            [["Authorization" => "Bearer"], true, null],
            [["Authization" => "Bearer token"], false, null],
            [["Authization" => "token"], false, null],
            [["Not" => "token"], false, null],
            [["x-" => "-y"], false, null],
            [[], false, null],
        ];
    }
}

class ControllerTest extends AbstractController
{
    public function authenticatedDefaultAction()
    {
    }
    /**
     * @NotAuthenticated
     */
    public function notAuthenticatedAction()
    {
    }
    /**
     * @Authenticated("test name")
     */
    public function authenticatedAction()
    {
    }
    /**
     * @PublicAccess
     */
    public function publicAction()
    {
    }
}