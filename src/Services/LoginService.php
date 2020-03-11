<?php


namespace Quiz\Services;


use Exception;
use Framework\Http\Request;
use Quiz\Entity\User;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class LoginService extends AbstractService
{
    /**
     * LoginService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        parent::__construct($repositoryManager);
    }

    /**
     * @param array $credentials
     * @return EntityInterface|null
     * @throws Exception
     */
    public function login(array $credentials)
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        $entity = $repository->findOneBy([$credentials['name']]);
        if(!password_verify($credentials["password"], $entity->getPassword())) {
            //redirect back to the page
            throw new Exception("Wrong password");
        }

        return $entity;
    }
}