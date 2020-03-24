<?php


namespace Quiz\Service;


use Framework\Http\Request;
use Quiz\Entity\User;
use Quiz\Exception\UserAlreadyExistsException;
use Quiz\Factory\UserFactory;
use Quiz\Persistency\Repositories\UserRepository;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
class UserService
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
     * @param EntityInterface $user
     * @return bool
     * //maybe make getRepository configurable******************************************
     * @throws UserAlreadyExistsException
     */
    public function add(EntityInterface $user): bool
    {
        /** @var UserRepository $repository */
        $repository =  $this->repositoryManager->getRepository(User::class);

        if($repository->findBy(["name" => $user->getName(), "email" => $user->getEmail()],[],0, 0)) {
            throw new UserAlreadyExistsException($user);
        }

        return $repository->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @param EntityInterface $user
     * @return bool
     */
    public function update(EntityInterface $user): bool
    {
        return $this->repositoryManager->getRepository(User::class)->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return  $this->repositoryManager->getRepository(User::class)->getCount();
    }

    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getAll(int $limit, int $page): array
    {
       $offset = $limit * ($page - 1);

       return $this->repositoryManager->getRepository(User::class)->findBy([], [], $offset, $limit);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function userDetails(array $attributes)
    {
        if (!empty($attributes)) {
            return $this->repositoryManager->getRepository(User::class)->find($attributes['id']);
        }

        return "";
    }

    /**
     * @param array $quizInstances
     * @return array
     */
    public function getAllByQuizInstances(array $quizInstances): array
    {
        $userRepository = $this->repositoryManager->getRepository(User::class);
        $users = [];
        foreach ($quizInstances as $quizInstance) {
            $users[] = $userRepository->findOneBy(["id" => $quizInstance->getUserId()]);
        }

        return $users;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->repositoryManager->getRepository(User::class)->deleteById($id);
    }
}