<?php

namespace Gamboa\AdminBundle\Controller;

use Gamboa\AdminBundle\Annotation\NotAuthenticated;
use Gamboa\AdminBundle\Request\RegisterRequest;
use Gamboa\AdminBundle\Service\AuthService;
use Gamboa\AdminBundle\Request\LoginRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/auth")
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/register", methods={"GET"}, name="gadmin.auth.register")
     * @NotAuthenticated
     */
    public function registerAction(Request $req)
    {
        $request = new RegisterRequest($req);

        return ['rut' => $request->get('rut'), 'pass' => $request->get('password')];
    }

    /**
     * @Route("/login", methods={"GET"}, name="gadmin.auth.login")
     * @NotAuthenticated
     */
    public function loginAction(Request $req, AuthService $auth)
    {
        return $auth->login(new LoginRequest($req));
    }

    /**
     * @Route("/check", methods={"GET"}, name="gadmin.auth.check")
     */
    public function checkAction()
    {
        throw new NotFoundHttpException();
    }

    /**
     * @Route("/logout", methods={"GET"}, name="gadmin.auth.logout")
     */
    public function logoutAction()
    {
        throw new NotFoundHttpException();
    }
}
