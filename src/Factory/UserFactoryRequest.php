<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\User;

class UserFactoryRequest extends RequestAbstractFactory
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return User::class;
    }
}