<?php

namespace Gamboa\AdminBundle\Request;

use Gamboa\AdminBundle\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Gamboa\AdminBundle\Validation\Validation;
use Gamboa\AdminBundle\Constraint\Rut;
use Gamboa\AdminBundle\Helper\Format;

class RegisterRequest extends AbstractRequest
{
    function setParameters()
    {
        $this->addRequired("rut", "Debe ingresar un rut", [
            new Validation(new Assert\NotBlank(), "Debe ingresar un rut"),
            new Validation(new Rut(Format::RUT_FORMATTED)),
        ]);

        $this->addRequired("name", "Debe ingresar un nombre", [
            new Validation(new Assert\NotBlank(), "Debe ingresar un nombre")
        ]);

        $this->addRequired("email", "Debe ingresar un email", [
            new Validation(new Assert\NotBlank(), "Debe ingresar un email")
        ]);

        $this->addRequired("password", "Debe ingresar una contraseña", [
            new Validation(new Assert\NotBlank(), "Debe ingresar una contraseña"),
            new Validation(new Assert\Length(array(
                'min' => 8,
                'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres',
            )))
        ]);

        $this->addRequired("password_confirmation", "Debe repetir la contraseña", [
            new Validation(new Assert\NotBlank(), "Debe repetir una contraseña")
        ]);
    }

    protected function postValidation()
    {
        // split rut
        list($rut, $dv) = explode("-", $this->get("rut"));
        $rut = intval(str_replace(".", "", $rut));
        $this->set("rut", $rut);
        $this->set("dv", $dv);
    }
}