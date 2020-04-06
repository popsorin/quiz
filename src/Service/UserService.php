<?php


namespace Quiz\Service;


use Quiz\Entity\User;
use Quiz\Exception\UserAlreadyExistsException;
use Quiz\Persistency\Repositories\UserRepository;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
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

        if($repository->findByWithOrOperator(["name" => $user->getName(), "email" => $user->getEmail()],[],0, 0)) {
            throw new UserAlreadyExistsException($user);
        }

        return $repository->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @param EntityInterface $user
     * @return bool
     * @throws UserAlreadyExistsException
     */
    public function update(EntityInterface $user): bool
    {
        /** @var UserRepository $repository */
        $repository =  $this->repositoryManager->getRepository(User::class);
        if($repository->findByWithOrOperator(["name" => $user->getName(), "email" => $user->getEmail()],[],0, 0)) {
            throw new UserAlreadyExistsException($user);
        }

        return $repository->insertOnDuplicateKeyUpdate($user);
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
     * @param int $id
     * @return User
     */
    public function getUserDetails(int $id): User
    {
        return $this->repositoryManager->getRepository(User::class)->find($id);
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

    /**
     * This function returns a user from the database.
     * If no user was found in returns null.
     *
     * @param array $filters
     * @return User|null
     */
    public function findOneBy(array $filters): ?User
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        return ($repository->findByWithOrOperator($filters, [], 0, 0)[0]) ?? null;
    }
}