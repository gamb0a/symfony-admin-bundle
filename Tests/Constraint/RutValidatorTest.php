<?php

namespace Gamboa\AdminBundle\Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Gamboa\AdminBundle\Helper\Format;
use Gamboa\AdminBundle\Constraint\Rut;
use Gamboa\AdminBundle\Constraint\RutValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
class RutValidatorTest extends ConstraintValidatorTestCase
{

    protected function createValidator()
    {
        return new RutValidator();
    }

    /**
     * @dataProvider getExceptionValues
     */
    public function testThowFormatException($format, $value)
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate($value, new Rut($format));
    }

    /**
     * @dataProvider getValidFormatValues
     */
    public function testValidValuesFormat($format, $value)
    {
        $this->validator->validate($value, new Rut($format));
        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidFormatValues
     */
    public function testInvalidFormat($format, $value)
    {
        $constraint = new Rut($format);
        $this->validator->validate($value, $constraint);
        $this->buildViolation($constraint->messageFormat)
        ->assertRaised();
    }

    public function getValidFormatValues () {
        return [
            [Format::RUT_FORMATTED, '21.582.090-4'],
            [Format::RUT_FORMATTED, '15.846.327-k'],
            [Format::RUT_FORMATTED, '15.846.327-K'],
            [Format::RUT_FORMATTED, '7.740.250-0'],
            [Format::RUT_FORMATTED, '24.500-3'],
            [Format::RUT_FORMATTED, '1.002-3'],
            [Format::RUT_NO_DOTS, '21582090-4'],
            [Format::RUT_NO_DOTS, '15846327-k'],
            [Format::RUT_NO_DOTS, '15846327-K'],
            [Format::RUT_NO_DOTS, '7740250-0'],
            [Format::RUT_NO_DOTS, '24500-3'],
            [Format::RUT_NO_DOTS, '1002-2'],
            [Format::RUT_NUMBER_ONLY, '21582090'],
            [Format::RUT_NUMBER_ONLY, '15846327'],
            [Format::RUT_NUMBER_ONLY, '7740250'],
            [Format::RUT_NUMBER_ONLY, '24500'],
            [Format::RUT_NUMBER_ONLY, '1002'],
            [Format::RUT_NUMBER_ONLY, 21582090],
            [Format::RUT_NUMBER_ONLY, 15846327],
            [Format::RUT_NUMBER_ONLY, 7740250],
            [Format::RUT_NUMBER_ONLY, 24500],
            [Format::RUT_NUMBER_ONLY, 1002],
            [Format::RUT_DV_ONLY, 1],
            [Format::RUT_DV_ONLY, 'k'],
            [Format::RUT_DV_ONLY, 'K'],
            [Format::RUT_DV_ONLY, '0'],
            [Format::RUT_DV_ONLY, 0]
        ];
    }

    public function getExceptionValues () {
        return [
            [Format::RUT_NUMBER_ONLY, null],
            [Format::RUT_NUMBER_ONLY, array('foo', 'bar')],
            [Format::RUT_NUMBER_ONLY, new \stdClass],
        ];
    }

    public function getInvalidFormatValues () {
        return [
            [Format::RUT_FORMATTED, '17.87823'],
            [Format::RUT_FORMATTED, '01.878.323-5'],
            [Format::RUT_FORMATTED, '017.87823-5'],
            [Format::RUT_FORMATTED, '0178.07823-5'],
            [Format::RUT_FORMATTED, '2-3'],
            [Format::RUT_FORMATTED, '17807823'],
            [Format::RUT_FORMATTED, '017807823'],
            [Format::RUT_NO_DOTS, '2-3'],
            [Format::RUT_NO_DOTS, 787823],
            [Format::RUT_NO_DOTS, 2],
            [Format::RUT_NO_DOTS, '00787823'],
            [Format::RUT_NO_DOTS, '01787823-5'],
            [Format::RUT_NO_DOTS, '017807823-5'],
            [Format::RUT_NO_DOTS, '17.807.823-5'],
            [Format::RUT_NO_DOTS, '017807823'],
            [Format::RUT_NUMBER_ONLY, '17807823-5'],
            [Format::RUT_NUMBER_ONLY, 'A'],
            [Format::RUT_NUMBER_ONLY, '01'],
            [Format::RUT_NUMBER_ONLY, '01123'],
            [Format::RUT_DV_ONLY, 12],
            [Format::RUT_DV_ONLY, '-a'],
            [Format::RUT_DV_ONLY, '01'],
            [Format::RUT_DV_ONLY, 'LL'],
            [Format::RUT_DV_ONLY, '0L'],
        ];
    }
}