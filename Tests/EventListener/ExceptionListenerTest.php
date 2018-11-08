<?php 

namespace Gamboa\AdminBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Gamboa\AdminBundle\EventListener\ExceptionListener;
use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Gamboa\AdminBundle\Exception\ForbiddenHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListenerTest extends TestCase
{
    /**
     * @param Exception $exception
     * 
     * @dataProvider exceptionsProvider
     */
    public function testWillReturnJsonAsResponse(\Exception $exception) {
        $event = $this->getResponseForExceptionEvent($exception);
        $listener = new ExceptionListener();
        $event->expects($this->once())
            ->method("setResponse")
            ->with($this->isInstanceOf(JsonResponse::class));
            $listener->onKernelException($event);
    }
        
    public function testWillReturnPayloadForBadRequest() {
        $badRequestException = new BadRequestHttpException(
            ["field" => "invalid field", "error" => "error message"]
        );
        $event = $this->getResponseForExceptionEvent($badRequestException);

        $event->expects($this->once())
            ->method("setResponse")
            ->with($this->callback(function($response) {
                $jsonResponsePayload = json_decode($response->getContent(), true);
                $this->assertInternalType("array", $jsonResponsePayload);
                $this->assertArrayHasKey("errors", $jsonResponsePayload);
                return $response;
              }));
        $listener = new ExceptionListener();
        $listener->onKernelException($event);
    }

    public function getResponseForExceptionEvent($exception) {
        
        $responseForExceptionEvent = $this->getMockBuilder(GetResponseForExceptionEvent::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $responseForExceptionEvent->expects($this->once())
            ->method("getException")
            ->will($this->returnValue($exception));

        return $responseForExceptionEvent;
    }

    public function exceptionsProvider() {
        $badRequestException   = new BadRequestHttpException();
        $forebiddenException   = new ForbiddenHttpException();
        $accessDeniedException = new AccessDeniedHttpException();

        return [
            [$badRequestException, $badRequestException->getStatusCode()],
            [$forebiddenException, $forebiddenException->getStatusCode()],
            [$accessDeniedException, $accessDeniedException->getStatusCode()],
            [new \Exception, Response::HTTP_INTERNAL_SERVER_ERROR]
        ];
    }
}