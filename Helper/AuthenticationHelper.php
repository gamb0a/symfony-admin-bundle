<?php

namespace Gamboa\AdminBundle\Helper;

class AuthenticationHelper
{
    public static function newToken() {
        return bin2hex(random_bytes(16));
    }
}