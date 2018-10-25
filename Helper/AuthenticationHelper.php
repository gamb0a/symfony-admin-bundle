<?php

namespace Gamboa\AdminBundle\Helper;

class AuthenticationHelper
{
    public static function newToken() : string {
        return bin2hex(random_bytes(32));
    }

    public static function passwordHash(string $password) : string {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}