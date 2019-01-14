<?php

namespace Gamboa\AdminBundle\Controller;

use Gamboa\AdminBundle\Annotation\Authenticated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actions")
 */
class ActionController extends AbstractController
{
    /**
     * @Route("/dump", methods={"GET"}, name="gadmin.actions.dump")
     * @Authenticated("gadmin.actions.dump", description="Listar todas las acciones")
     */
    public function dump()
    {
        return [];
    }

    /**
     * @Route("/{actionId}", methods={"GET"}, name="gadmin.actions.show")
     * @Authenticated("gadmin.actions.show", description="Mostrar acción")
     */
    public function show($actionId)
    {
        return [];
    }

}
