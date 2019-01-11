<?php

namespace Gamboa\AdminBundle\Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Gamboa\AdminBundle\Helper\Format;
use Gamboa\AdminBundle\Constraint\Rut;

class RutTest extends TestCase
{
    /**
     * @dataProvider rutConstructorProvider
     */
    public function testSetFormat($value, $expected)
    {
        $rut = new Rut($value);
        $this->assertEquals($rut->format, $expected);
    }

    public function rutConstructorProvider () {
        return [
            [Format::RUT_NUMBER_ONLY, Format::RUT_NUMBER_ONLY],
            [Format::RUT_DV_ONLY, Format::RUT_DV_ONLY] ,
            [Format::RUT_NO_DOTS, Format::RUT_NO_DOTS],
            [Format::RUT_FORMATTED, Format::RUT_FORMATTED],
            [null, Format::RUT_FORMATTED],
            ['', Format::RUT_FORMATTED],
            [1, Format::RUT_FORMATTED],
            [0, Format::RUT_FORMATTED]
        ];
    }
}