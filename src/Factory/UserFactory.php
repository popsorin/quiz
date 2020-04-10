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
        $name = isset($parameters[$nameKey]) ? $parameters[$nameKey] : "";
        $email = isset($parameters[$emailKey]) ? $parameters[$emailKey] : " ";
        $password = isset($parameters[$passwordKey]) ? $parameters[$passwordKey] : "";
        $role = isset($parameters[$roleKey]) ? $parameters[$roleKey] : "";

        return new User($name, $email, $password, $role);
    }
}