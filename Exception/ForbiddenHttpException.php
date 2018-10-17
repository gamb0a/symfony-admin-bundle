<?php

namespace Gamboa\AdminBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenHttpException extends HttpException
{
    public function __construct(string $message = "Forbidden", \Exception $previous = null, ? int $code = 0, array $headers = array())
    {
        parent::__construct(403, $message, $previous, $headers, $code);
    }
}