<?php

namespace Quiz\Service;

use Exception;
use Framework\Http\Request;
use Quiz\Entity\User;
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
     * @return EntityInterface|null
     * @throws Exception
     */
    public function login(array $credentials)
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        $entity = $repository->findOneBy(["name" =>$credentials['name']]);
        if(!password_verify($credentials["password"], $entity->getPassword())) {
            //redirect back to the page
            throw new Exception("Wrong password");
        }

        return $entity;
    }
}