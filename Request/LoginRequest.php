<?php

namespace Gamboa\AdminBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Gamboa\AdminBundle\Validation\Validation;
use Gamboa\AdminBundle\Constraint\Rut;
use Gamboa\AdminBundle\Helper\Format;

class LoginRequest extends AbstractRequest
{
    public function setParameters()
    {
        $this->addRequired('rut', 'Debe ingresar un rut', [
            new Validation(new Assert\NotBlank(), 'Debe ingresar un rut'),
            new Validation(new Rut(Format::RUT_NO_DOTS), 'Debe ingresar un rut valido'),
        ]);

        $this->addRequired('password', 'Debe ingresar una contraseña', [
            new Validation(new Assert\NotBlank(), 'Debe ingresar una contraseña'),
        ]);
    }

    protected function postValidation()
    {
        list($rut, $dv) = explode('-', $this->get('rut'));
        $rut = intval(str_replace('.', '', $rut));
        $this->set('rut', $rut);
        $this->set('dv', $dv);
    }
}
