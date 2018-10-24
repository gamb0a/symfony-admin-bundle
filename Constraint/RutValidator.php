<?php

namespace Gamboa\AdminBundle\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Gamboa\AdminBundle\Helper\Format;

/**
 * @Annotation
 */
class RutValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        // By default Format::RUT_FORMATTED
        $exp = '/^\d{1,2}\.\d{3}\.\d{3}[-][0-9kK]{1}$/';
        if ($constraint->format == Format::RUT_NUMBER_ONLY) {
            $exp = '/^[0-9]+$/';
        } elseif ($constraint->format == Format::RUT_DV_ONLY) {
            $exp = '/^[0-9Kk]{1}$/';
        } elseif ($constraint->format == Format::RUT_NO_DOTS) {
            $exp = '/^\d{1,2}\d{3}\d{3}[-][0-9kK]{1}$/';
        }

        if (!preg_match($exp, $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}