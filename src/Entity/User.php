<?php

namespace Quiz\Entity;

use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class User
 */
class User extends AbstractEntity
{
    /**
     * @var string
     * @MappedOn name
     */
    private $name;

    /**
     * @var string
     * @MappedOn email
     */
    private $email;

    /**
     * @var string
     * @MappedOn password
     */
    private $password;

    /**
     * @var string
     * @MappedOn role
     */
    private $role;

    /**
     * User constructor.
     * @param string $name
     * @param string $password
     * @param string $role
     * @param string $email
     */
    public function __construct(
        string $name,
        string $password,
        string $role,
        string $email
    )
    {
        $this->name = $name;
        $this->password = $password;
        $this->role = $role;
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return EntityInterface
     */
    public function setEmail(string $email): EntityInterface
    {
        $this->email = $email;

        return $this;
    }
}