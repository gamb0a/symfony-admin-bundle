<?php 

namespace Gamboa\AdminBundle\Service;

use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Gamboa\AdminBundle\Entity\User;
use Gamboa\AdminBundle\Request\LoginRequest;
use Gamboa\AdminBundle\Request\RegisterRequest;
use Gamboa\AdminBundle\Helper\AuthenticationHelper;

class AuthService
{

    private $userService;
    private $sessionService;

    function __construct(UserService $userService, SessionService $sessionService) {
        $this->userService = $userService;
        $this->sessionService = $sessionService;
    }

    public function isValid(string $token) : bool
    {
        return $this->sessionService->tokenIsValid($token);
    }

    public function getUser(string $token) : User
    {
        return $this->userService->getUserBytoken($token);
    }

    public function login(LoginRequest $req) : array
    {
        $rut = $req->get("rut");
        $password = $req->get("password");

        try {
            $user = $this->userService->getUserByRut($rut);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(["general" => "Rut y/o Contraseña inválidos"]);
        }
        
        // if its the correct password
        if (!$user->passwordEqualsTo($password))
            throw new BadRequestHttpException(["general" => "Rut y/o Contraseña inválidos"]); 

        // if its active
        if (!$user->isActive())
            throw new BadRequestHttpException(["general" => "La cuenta no está activa"]);

        // generate a new token sessio for the given user
        return $this->sessionService->generateTokenForUser($user);
    }

    public function register(RegisterRequest $req)
    {
        return $this->userService->add(
            $req->get("rut"),
            $req->get("dv"),
            $req->get("name"),
            $req->get("username"),
            $req->get("email"),
            $req->get("password")
        );
    }
}