<?php 

namespace Gamboa\AdminBundle\Service;

use Gamboa\AdminBundle\Request\LoginRequest;
use Gamboa\AdminBundle\Request\RegisterRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

class AuthService
{
    function isValid(string $token) : boolean
    {
        return false;
    }

    function getUser(string $token) : ParameterBag
    {
        return new ParameterBag([
            "name" => "User",
            "rut" => "11.000.111-1",
            "actions" => []
        ]);
    }

    function login(LoginRequest $req)
    {

    }

    function register(RegisterRequest $req)
    {

    }
}