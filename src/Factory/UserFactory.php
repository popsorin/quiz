<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\User;

class UserFactory
{
    /**
     * @param Request $request
     * @param string $nameKey
     * @param string $emailKey
     * @param string $passwordKey
     * @param string $roleKey
     * @return User
     */
    public function createFromRequest(
        Request $request,
        string $nameKey,
        string $emailKey,
        string $passwordKey,
        string $roleKey
    ): User {
        $parameters = $request->getParameters();
        $name = $parameters[$nameKey];
        $email = $parameters[$emailKey];
        $password = $parameters[$passwordKey];
        $role = $parameters[$roleKey];

        return new User($name, $email, $password, $role);
    }
}