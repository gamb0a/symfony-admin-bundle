<?php

namespace Gamboa\AdminBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Gamboa\AdminBundle\Request\LoginRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class LoginRequestTest extends TestCase
{
    /**
     * @param string rut
     * @param string password
     *
     * @dataProvider providerInvalidParams
     */
    public function testThrowExceptionWithInvalidParams($rut, $password)
    {
        $this->expectException(BadRequestHttpException::class);
        $loginRequest = new LoginRequest($this->getRequest($rut, $password));
    }

    public function testThrowExceptionWithoutParams()
    {
        $this->expectException(BadRequestHttpException::class);
        $loginRequest = new LoginRequest(new Request());
    }

    /**
     * @param string rut
     * @param string password
     * @param int    excpetedRut
     * @param string excpetedDv
     *
     * @dataProvider providerValidParams
     */
    public function testGetParamsAfterValidation($rut, $password, $expectedRut, $expectedDv)
    {
        $loginRequest = new LoginRequest($this->getRequest($rut, $password));

        $this->assertEquals($expectedRut, $loginRequest->get('rut'));
        $this->assertEquals($expectedDv, $loginRequest->get('dv'));
        $this->assertEquals($password, $loginRequest->get('password'));
        $this->assertInternalType('int', $loginRequest->get('rut'));
    }

    public function getRequest($rut, $password)
    {
        $request = new Request();
        $params = new ParameterBag([
            'rut' => $rut,
            'password' => $password,
        ]);
        $request->request = $params;

        return $request;
    }

    public function providerValidParams()
    {
        return [
            ['24500-3', 'dummypass', 24500, '3'],
            ['21582090-4', 'dummypass', 21582090, '4'],
            ['23807823-7', 'dummypass', 23807823, '7'],
        ];
    }

    public function providerInvalidParams()
    {
        return [
            ['23.807.823-2', 'dummypass'],
            ['23.807823-2', 'dummypass'],
            ['23.807823-7', null],
            ['23807823-7', ''],
            ['', 'pass'],
            [null, 'pass'],
        ];
    }
}
