<?php 

namespace Gamboa\AdminBundle\Entity;

class User
{

    const STATUS_ACTIVE = 'Activo';
    const STATUS_DELETED = 'Eliminado';
    const STATUS_SUSPENDED = 'Suspendido';

    private $id;
    private $rut;
    private $dv;
    private $rutFormateado;
    private $name;
    private $email;
    private $password;
    private $status;
    private $username;
    private $createdAt;
    private $modifiedAt;
    private $lastPasswordChange;

    function __construct(int $id, int $rut, string $dv, string $rutFormateado, string $name, string $email,
                        string $password, string $status, string $username, \DateTime $createdAt, \DateTime $modifiedAt,
                        \DateTime $lastPasswordChange) {
        $this->id = $id;
        $this->rut = $rut;
        $this->dv = $dv;
        $this->rutFormateado = $rutFormateado;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->username = $username;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
        $this->lastPasswordChange = $lastPasswordChange;
    }

    public function passwordEqualsTo(string $password) : bool {
        return false;
    }
    
    public function isActive() : bool {
        return $status === self::STATUS_ACTIVE;
    }
}