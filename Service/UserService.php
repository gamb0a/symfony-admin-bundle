<?php 

namespace Gamboa\AdminBundle\Service;

use Gamboa\AdminBundle\Entity\User;
use Gamboa\AdminBundle\Helper\AuthenticationHelper;
use Doctrine\DBAL\Connection;

class UserService
{
    private $connetion;

    function __construct(Connection $connetion) {
        $this->connection = $connetion;
    }

    public function add($rut, $dv, $name, $username, $email, $password, $isAdmin = false) : int 
    {
        if ($this->existsByRut($rut)) {
            throw new \Exception("Ya existe un usuario con ese rut");
        }
        
        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare("INSERT INTO user
            (rut, dv, name, username, email, password, is_admin)
        VALUE
            (:rut, :dv, :name, :username, :email, :password, :is_admin)");
        $stmt->bindValue("rut", $rut);
        $stmt->bindValue("dv", $dv);
        $stmt->bindValue("name", $name);
        $stmt->bindValue("username", $username);
        $stmt->bindValue("email", $email);
        $stmt->bindValue("password", AuthenticationHelper::passwordHash($password));
        $stmt->bindValue("is_admin", $isAdmin);
        $stmt->execute();
        $userId = $this->connection->lastInsertId();
        $this->connection->commit();

        return $userId;
    }
    
    public function existsByRut(string $rut) : bool
    {
        $stmt = $this->connection->prepare("SELECT id FROM user WHERE rut = :rut");
        $stmt->bindValue("rut", $rut);
        $stmt->execute();
        return ($stmt->rowCount() > 0);
    }
    
    public function getUserByToken(string $token) : User
    {
        $stmt = $this->connection->prepare("SELECT 
            user.id, rut, dv, name,
            CONCAT(FORMAT(rut, 0, 'de_DE'), '-', dv) AS rut_formateado,
            COALESCE(username, '') AS username,
            email, password, status,
            last_password_change, user.created_at, user.modified_at
        FROM user 
            INNER JOIN session ON session.user = user.id
        WHERE session.token = :token");
        $stmt->bindValue("token", $token);
        $stmt->execute();
        
        if ($stmt->rowCount() <= 0) {
            throw new \Exception("No existe el usuario");
        }

        $userFetch = $stmt->fetch();
        $user = $this->createFromFetch($userFetch);
        return $user;
    }
    
    public function getUserByRut(string $rut) : User
    {
        $stmt = $this->connection->prepare("SELECT 
            id, rut, dv, name,
            CONCAT(FORMAT(rut, 0, 'de_DE'), '-', dv) AS rut_formateado,
            COALESCE(username, '') AS username,
            email, password, status,
            last_password_change, created_at, modified_at
        FROM user WHERE rut = :rut");
        $stmt->bindValue("rut", $rut);
        $stmt->execute();
        
        if ($stmt->rowCount() <= 0) {
            throw new \Exception("No existe el usuario");
        }

        $userFetch = $stmt->fetch();
        $user = $this->createFromFetch($userFetch);
        return $user;
    }

    private function createFromFetch(array $userFetch) : User 
    {
        return new User(
            $userFetch["id"], $userFetch["rut"], $userFetch["dv"], $userFetch["rut_formateado"], 
            $userFetch["name"], $userFetch["email"], $userFetch["password"], $userFetch["status"], 
            $userFetch["username"], new \DateTime($userFetch["created_at"]), 
            new \DateTime($userFetch["modified_at"]), new \DateTime($userFetch["last_password_change"])
        );
    }
}