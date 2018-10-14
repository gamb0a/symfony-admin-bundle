<?php

namespace Gamboa\AdminBundle\Request;

use Gamboa\AdminBundle\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Gamboa\AdminBundle\Validation\Validation;

class LoginRequest extends AbstractRequest
{
    function setParameters()
    {
        $this->addRequired("rut", "Debe ingresar un rut", [
            new Validation(new Assert\NotBlank(), "Debe ingresar un rut")
        ]);

        $this->addRequired("password", "Debe ingresar una contraseña", [
            new Validation(new Assert\NotBlank(), "Debe ingresar una contraseña")
        ]);
    }
}