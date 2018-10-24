<?php 

namespace Gamboa\AdminBundle\Service;

use Gamboa\AdminBundle\Entity\User;
use Gamboa\AdminBundle\Helper\AuthenticationHelper;

class SessionService
{
    public function generateTokenForUser(User $user) {
        $token = AuthenticationHelper::newToken();
        // TODO 
        // - invalidate current session if exists
        // - generate new session
        return $token;
    }
}