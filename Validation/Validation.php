<?php 

namespace Gamboa\AdminBundle\Validation;

use Symfony\Component\Validator\Constraint;

class Validation
{
    private $constraint;

    function __construct(Constraint $constraint, string $message = "")
    {
        $this->constraint = $constraint;
        if (!empty($message))
            $this->constraint->message = $message;
    }

    public function getConstraint()
    {
        return $this->constraint;
    }
}