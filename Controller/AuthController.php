<?php

namespace Gamboa\AdminBundle\Controller;

use Gamboa\AdminBundle\Annotation\PublicAction;
use Gamboa\AdminBundle\Annotation\NotAuthenticated;
use Gamboa\AdminBundle\Annotation\Authenticated;

use Gamboa\AdminBundle\Request\RegisterRequest;
use Gamboa\AdminBundle\Request\LoginRequest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/auth")
 */
class AuthController extends Controller
{
    /**
     * @Route("/register", methods={"GET"}, name="gadmin.auth.register")
     * @NotAuthenticated
     */
    public function register(Request $req)
    {
        $request = new RegisterRequest($req);
        return ["rut" => $request->get("rut"), "pass" => $request->get("password")];
    }

    /**
     * @Route("/login", methods={"GET"}, name="gadmin.auth.login")
     * @Authenticated("gadmin.auth.login", description="Login into the app")
     */
    public function login(Request $req)
    {
        $request = new LoginRequest($req);
        return ["rut" => $request->get("rut"), "pass" => $request->get("password")];
    }

    /**
     * @Route("/check", methods={"GET"}, name="gadmin.auth.check")
     * @Authenticated("gadmin.auth.check", description="Check current session")
     */
    public function check()
    {
        throw new NotFoundHttpException();
    }

    /**
     * @Route("/logout", methods={"GET"}, name="gadmin.auth.logout")
     * @Authenticated("gadmin.auth.logout", description="Logout current session")
     */
    public function logout()
    {
        throw new NotFoundHttpException();
    }
}