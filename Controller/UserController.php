<?php

namespace Gamboa\AdminBundle\Controller;

use Gamboa\AdminBundle\Annotation\Authenticated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="gadmin.users.list")
     * @Authenticated("gadmin.admin.list-users", description="Listar todos los usuarios")
     */
    public function listAction()
    {
        return [];
    }

    /**
     * @Route("/change-password", methods={"POST"}, name="gadmin.users.change-password")
     * @Authenticated("gadmin.users.change-password", description="Cambiar la contraseña del usuario autenticado")
     */
    public function changePasswordAction()
    {
        return [];
    }

    /**
     * @Route("/{userId}/reset-password", methods={"POST"}, name="gadmin.users.reset-password")
     * @Authenticated("gadmin.admin.reset-password", description="Reestablecer la contraseña de un usuario")
     */
    public function resetPasswordAction($userId)
    {
        return [];
    }

    /**
     * @Route("/{userId}", methods={"GET"}, name="gadmin.users.show")
     * @Authenticated("gadmin.users.show", description="Mostrar usuario")
     */
    public function showAction($userId)
    {
        return [];
    }

}
