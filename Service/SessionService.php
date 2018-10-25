<?php 

namespace Gamboa\AdminBundle\Service;

use Doctrine\DBAL\Connection;
use Gamboa\AdminBundle\Entity\User;
use Gamboa\AdminBundle\Helper\AuthenticationHelper;

class SessionService
{

    private $connetion;

    function __construct(Connection $connetion) {
        $this->connection = $connetion;
    }

    public function generateTokenForUser(User $user) {

        // By default, each session lasts 30 min
        $ttl_min = 30;
        if (isset($_ENV["SESSION_TTL_MIN"]))
            $ttl_min = intval($_ENV["SESSION_TTL_MIN"]);

        $this->connection->beginTransaction();
        $this->invalidateAllFromUser($user, 'Inicia sesión desde otro dispositivo');

        $token = AuthenticationHelper::newToken();
        $refresh_token = AuthenticationHelper::newToken();

        $stmt = $this->connection->prepare("INSERT INTO session 
            (token, refresh_token, user, created_at, expires_at, refreshed_at, is_valid)
        VALUES
            (:token, :refresh_token, :user, NOW(), DATE_ADD(NOW(), INTERVAL :ttl MINUTE), NOW(), 1)");
        $stmt->bindValue("user", $user->getId());
        $stmt->bindValue("token", $token);
        $stmt->bindValue("refresh_token", $refresh_token);
        $stmt->bindValue("ttl", $ttl_min);
        $stmt->execute();
        $this->connection->commit();

        return ["token" => $token, "refresh_token" => $refresh_token];
    }

    public function tokenIsValid(string $token) :bool
    {
        $stmt = $this->connection->prepare("SELECT id FROM session WHERE token = :token AND is_valid = 1");
        $stmt->bindValue("token", $token);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }

    private function invalidateAllFromUser(User $user, string $reason = "No especifica razón") {
        $stmt = $this->connection->prepare("UPDATE session 
            SET invalid_reason = :reason, is_valid = 0, invalidated_at = NOW()
            WHERE user = :user");
        $stmt->bindValue("user", $user->getId());
        $stmt->bindValue("reason", $reason);
        $stmt->execute();
    }
}