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
    private $actions;

    public function __construct(
        int $id,
        int $rut,
        string $dv,
        string $rutFormateado,
        string $name,
        string $email,
                        string $password,
        string $status,
        string $username,
        \DateTime $createdAt,
        \DateTime $modifiedAt,
                        \DateTime $lastPasswordChange,
        $actions = []
    ) {
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
        $this->actions = $actions;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function hasAction(string $action): bool
    {
        foreach ($this->actions as $key => $action) {
            if ($action->getCode() === $action) {
                return true;
            }
        }

        return false;
    }

    public function getActions(): array
    {
        return $this->password;
    }

    public function passwordEqualsTo(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function isActive(): bool
    {
        return self::STATUS_ACTIVE === $this->status;
    }
}
