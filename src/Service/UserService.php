<?php


namespace Quiz\Service;


use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Service\Exception\UserAlreadyExistsException;
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
     */
    public function add(EntityInterface $user): bool
    {
        /** @var UserRepository $repository */
        $repository = $this->repositoryManager->getRepository(User::class);
        return $repository->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @param EntityInterface $user
     * @return bool
     */
    public function update(EntityInterface $user): bool
    {
        /** @var UserRepository $repository */
        $repository = $this->repositoryManager->getRepository(User::class);

        return $repository->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @param array $filters
     * @return int
     */
    public function getCount(array $filters): int
    {
        return $this->repositoryManager->getRepository(User::class)->getCount($filters);
    }

    /**
     * @param array $filter
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getAll(array $filter, int $limit, int $page): array
    {
        $offset = $limit * ($page - 1);

        return $this->repositoryManager->getRepository(User::class)->findBy($filter, [], $offset, $limit);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findUserById(int $id): ?User
    {
        return $this->repositoryManager->getRepository(User::class)->find($id);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->repositoryManager->getRepository(User::class)->findOneBy(["email" => $email]);
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