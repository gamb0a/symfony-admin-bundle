<?php

namespace Gamboa\AdminBundle\Request;

use Gamboa\AdminBundle\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Gamboa\AdminBundle\Validation\Validation;

class RegisterRequest extends AbstractRequest
{
    function setParameters()
    {
        $this->addRequired("rut", "Debe ingresar un rut", [
            new Validation(new Assert\NotBlank(), "Debe ingresar un rut")
        ]);

        $this->addRequired("name", "Debe ingresar un nombre", [
            new Validation(new Assert\NotBlank(), "Debe ingresar un nombre")
        ]);
        
        $this->addRequired("password", "Debe ingresar una contraseña", [
            new Validation(new Assert\NotBlank(), "Debe ingresar una contraseña"),
            new Validation(new Assert\Length(array(
                'min'        => 8,
                'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres',
            )))
        ]);

        $this->addRequired("password_confirmation", "Debe repetir la contraseña", [
            new Validation(new Assert\NotBlank(), "Debe repetir una contraseña")
        ]);
    }
}