<?php

namespace Quiz\Entitie;

use ReallyOrm\Entity\AbstractEntity;

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
     */
    public function __construct(string $name, string $password, string $role)
    {
        $this->name = $name;
        $this->password = $password;
        $this->role = $role;
    }
}