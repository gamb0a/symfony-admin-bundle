<?php 

namespace Gamboa\AdminBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Gamboa\AdminBundle\Request\RegisterRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Gamboa\AdminBundle\Validation\Validation;
use Symfony\Component\HttpFoundation\Request;

class RegisterRequestTest extends TestCase
{
    /**
     * @param string rut
     * @param string name
     * @param string email
     * @param string password
     * @param string password_confirmation
     * 
     * @dataProvider providerInvalidParams
     */
    public function testThrowExceptionWithInvalidParams($rut, $name, $email, $password, $password_confirmation)
    {
        $this->expectException(BadRequestHttpException::class);
        new RegisterRequest($this->getRequest($rut, $name, $email, $password, $password_confirmation));
    }

    public function testThrowExceptionWithoutParams()
    {
        $this->expectException(BadRequestHttpException::class);
        new RegisterRequest(new Request());
    }
    
    /**
     * @param string rut
     * @param string name
     * @param string email
     * @param string password
     * @param string password_confirmation
     * @param int    excpetedRut
     * @param string excpetedDv
     * 
     * @dataProvider providerValidParams
     */
    public function testGetParamsAfterValidation($rut, $name, $email, $password, $password_confirmation, $expectedRut, $expectedDv)
    {
        $request = new RegisterRequest($this->getRequest($rut, $name, $email, $password, $password_confirmation));
        $this->assertEquals($expectedRut, $request->get("rut"));
        $this->assertEquals($expectedDv, $request->get("dv"));
        $this->assertEquals($password, $request->get("password"));
        $this->assertInternalType("int", $request->get("rut"));
    }

    public function getRequest($rut, $name, $email, $password, $password_confirmation) {
        $request = new Request();
        $params = new ParameterBag([
            "rut" => $rut,
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "password_confirmation" => $password_confirmation
        ]);
        $request->request = $params;
        return $request;
    }

    public function providerValidParams()
    {
        return array(
            array('31177706-8', 'nicolas', 'nn@gmail.com', 'password11!', 'password11!', 31177706, '8'),
            array('37669903-K', 'peter escariola', 'peter@hotmail.com', 'passw0rd11!', 'passw0rd11!', 37669903, 'K')
        );
    }

    public function providerInvalidParams()
    {
        return array(
            array('1.245.009-5', 'nicolas', 'invalid_email', '', 'invalid_confirmation'),
            array('27.767.951-5', 'dummyame', 'nn@gmail.com', '', 'pasword'),
            array('0000000', 'dummyame', 'nn@gmail.com', 'correctPass', 'correctPass'),
            array('32393785-0', 'dummyame', 'nn@gmail.com', 'correctPass', 'incorrectPass'),
            array('17807823-2', '', 'nn@gmail.com', 'correctPass', 'correctPass'),
            array(null, 'dummyame', 'nn@gmail.com', '', ''),
        );
    }
}