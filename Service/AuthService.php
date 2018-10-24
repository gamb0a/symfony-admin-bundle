<?php 

namespace Gamboa\AdminBundle\Service;

use Gamboa\AdminBundle\Request\LoginRequest;
use Gamboa\AdminBundle\Request\RegisterRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

class AuthService
{
    function isValid(string $token) : bool
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

    function login(LoginRequest $req, UserService $userService, SessionService $sessionService) : array
    {
        $rut = $req->get("rut");
        $password = $req->get("password");

        // if user exists
        if (!$userService->exists($rut)) ;
            throw new BadRequestHttpException("Hubo un error al procesar la solicitud", ["general" => "Rut y/o Contraseña inválidos"]); 
            
        $user = $userService->getUserByRut($rut);
        // if its the correct password
        if (!$user->passwordEqualsTo($password))
            throw new BadRequestHttpException("Hubo un error al procesar la solicitud", ["general" => "Rut y/o Contraseña inválidos"]); 

        // if its active
        if (!$user->isActive())
            throw new BadRequestHttpException("Hubo un error al procesar la solicitud", ["general" => "La cuenta no está activa"]);

        // generate a new token sessio for the given user
        $token = $sessionService->generateTokenForUser($user);

        return $token;
    }

    function register(RegisterRequest $req)
    {

    }
}