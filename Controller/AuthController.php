<?php

namespace Gamboa\AdminBundle\Controller;

use Gamboa\AdminBundle\Request\RegisterRequest;
use Gamboa\AdminBundle\Request\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/auth")
 */
class AuthController extends Controller
{
    /**
     * @Route("/register", methods={"GET"}, name="gadmin.auth.register")
     */
    public function register(Request $req)
    {
        $request = new RegisterRequest($req);
        return ["rut" => $request->get("rut"), "pass" => $request->get("password")];
    }

    /**
     * @Route("/login", methods={"GET"}, name="gadmin.auth.login")
     */
    public function login(Request $req)
    {
        $request = new LoginRequest($req);
        return ["rut" => $request->get("rut"), "pass" => $request->get("password")];
    }

    /**
     * @Route("/check", methods={"GET"}, name="gadmin.auth.check")
     */
    public function check()
    {
        throw new NotFoundHttpException();
    }

    /**
     * @Route("/logout", methods={"GET"}, name="gadmin.auth.logout")
     */
    public function logout()
    {
        throw new NotFoundHttpException();
    }
}