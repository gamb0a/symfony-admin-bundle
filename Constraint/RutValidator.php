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
        if (!is_string($value) && !is_int($value)) {
            throw new UnexpectedTypeException($value, 'string|int');
        }

        // By default Format::RUT_FORMATTED
        $exp = '/^([1-9]{1}\d{1}|[1-9]{1})\.\d{3}(\.\d{3}){0,1}[-][0-9kK]{1}$/';
        if (Format::RUT_NUMBER_ONLY == $constraint->format) {
            $exp = '/^([1-9]{1}\d{1}|[1-9]{1})\d{3}(\d{3}){0,1}$/';
        } elseif (Format::RUT_DV_ONLY == $constraint->format) {
            $exp = '/^[0-9Kk]{1}$/';
        } elseif (Format::RUT_NO_DOTS == $constraint->format) {
            $exp = '/^([1-9]{1}\d{1}|[1-9]{1})\d{3}(\d{3}){0,1}[-][0-9kK]{1}$/';
        }

        if (!preg_match($exp, $value, $matches)) {
            $this->context
                ->buildViolation($constraint->messageFormat)
                ->addViolation();

            return;
        }

        // Logic Validation
        if (in_array($constraint->format, [Format::RUT_NO_DOTS, Format::RUT_NO_DOTS])) {
            if (false === $this->validateRut($value)) {
                $this->context
                    ->buildViolation($constraint->messageInvalid)
                    ->addViolation();
            }
        }
    }

    public function validateRut($rut)
    {
        $rut = preg_replace('/[\.\-]/i', '', $rut);
        $dv = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut) - 1);
        $i = 2;
        $suma = 0;

        foreach (array_reverse(str_split($numero)) as $v) {
            if (8 == $i) {
                $i = 2;
            }
            $suma += $v * $i;
            ++$i;
        }

        $dvr = 11 - ($suma % 11);
        if (11 == $dvr) {
            $dvr = 0;
        }
        if (10 == $dvr) {
            $dvr = 'K';
        }
        if ($dvr == strtoupper($dv)) {
            return true;
        } else {
            return false;
        }
    }
}
