<?php

namespace Gamboa\AdminBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BadRequest;

class BadRequestHttpException extends BadRequest
{
    protected $errorList;

    public function __construct(array $errorList = [], string $message = "Hubo un error al procesar la solicitud")
    {
        $this->errorList = $errorList;
        parent::__construct($message);
    }

    public function getErrorList()
    {
        return $this->errorList;
    }
}