<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\User;

class UserFactory extends AbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return User::class;
    }
}