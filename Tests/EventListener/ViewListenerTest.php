<?php

namespace Gamboa\AdminBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Gamboa\AdminBundle\EventListener\ViewListener;

class ViewListenerTest extends TestCase
{
    /**
     * @param mixed $controllerResult
     *
     * @dataProvider controllerResultProvider
     */
    public function testWillThrowUnexpectedValueExceptionIfNotArray($controllerResult)
    {
        $this->expectException(\UnexpectedValueException::class);

        $event = $this->getMockBuilder(GetResponseForControllerResultEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->once())
            ->method('getControllerResult')
            ->will($this->returnValue($controllerResult));

        $listener = new ViewListener();
        $listener->onKernelView($event);
    }

    public function testWillReturnJsonResponse()
    {
        $arrayResponse = ['field1' => 'flied1 data', 'field2' => 'field2 data'];
        $event = $this->getMockBuilder(GetResponseForControllerResultEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->once())
            ->method('getControllerResult')
            ->will($this->returnValue($arrayResponse));

        $event->expects($this->once())
            ->method('setResponse')
            ->with($this->callback(function ($response) use ($arrayResponse) {
                $this->assertInstanceOf(JsonResponse::class, $response);
                $jsonResponsePayload = json_decode($response->getContent(), true);
                $this->assertInternalType('array', $jsonResponsePayload);
                $this->assertEquals($arrayResponse, $jsonResponsePayload);

                return $response;
            }));
        $listener = new ViewListener();
        $listener->onKernelView($event);
    }

    public function controllerResultProvider()
    {
        return [
            ['123123'],
            [123213],
            [new \Exception()],
            [new JsonResponse(['foo' => 'bar'])],
            ["{foo: 'bar'}"],
        ];
    }
}
