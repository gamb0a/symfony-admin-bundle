<?php

namespace Gamboa\AdminBundle\Controller;

use Gamboa\AdminBundle\Annotation\Authenticated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/action")
 */
class ActionController extends AbstractController
{
    /**
     * @Route("/dump", methods={"GET"}, name="gadmin.action.dump")
     * @Authenticated("gadmin.action.dump", description="Listar todas las acciones en el sistema")
     */
    public function dump()
    {
        return [];
    }
}
