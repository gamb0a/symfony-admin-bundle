<?php

namespace Gamboa\AdminBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BadRequest;

class BadRequestHttpException extends BadRequest
{
    protected $errorList;

    public function __construct(string $message = null, array $errorList = [])
    {
        $this->errorList = $errorList;
        parent::__construct($message);
    }

    public function getErrorList()
    {
        return $this->errorList;
    }
}