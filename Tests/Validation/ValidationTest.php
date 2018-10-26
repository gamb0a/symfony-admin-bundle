<?php 

namespace Gamboa\AdminBundle\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Gamboa\AdminBundle\Validation\Validation;
use Gamboa\AdminBundle\Constraint\Rut;

class ValidationTest extends TestCase
{
    public function testGetConstraint()
    {   
        $testMessage = "test message";
        $validation = new Validation(new Rut(), $testMessage);
        $this->assertInstanceOf(Rut::class, $validation->getConstraint());
        $this->assertEquals($testMessage, $validation->getConstraint()->message);
    }
}