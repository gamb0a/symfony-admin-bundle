<?php

namespace Gamboa\AdminBundle\Constraint;

use Gamboa\AdminBundle\Helper\Format;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Rut extends Constraint
{
    public $format;
    public $message = 'Rut inválido';
    public $messageFormat = 'Formato inválido';
    public $messageInvalid = 'El rut ingresado es inválido';

    public function __construct($format = null)
    {
        parent::__construct([]);
        if (!in_array($format, [Format::RUT_NUMBER_ONLY, Format::RUT_DV_ONLY, Format::RUT_NO_DOTS, Format::RUT_FORMATTED])) {
            $format = Format::RUT_FORMATTED;
        }
        $this->format = $format;
    }
}
