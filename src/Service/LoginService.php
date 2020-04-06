<?php

namespace Quiz\Service;

use Exception;
use Framework\Http\Request;
use Quiz\Entity\User;
use Quiz\Service\Exception\WrongPasswordException;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class LoginService
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * AdminService constructor.
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param array $credentials
     * @return User
     * @throws WrongPasswordException
     */
    public function login(array $credentials): User
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        $entity = $repository->findOneBy(["name" =>$credentials['name']]);
        if(!password_verify($credentials["password"], $entity->getPassword())) {
            //redirect back to the page
            throw new WrongPasswordException("Wrong password");
        }

        return $entity;
    }
}