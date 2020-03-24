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

    const LISTING_PAGE = "admin-users-listing.phtml";

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

    public function update(EntityInterface $user): bool
    {
        return $this->repositoryManager->getRepository(User::class)->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @param array $parameters
     * @param int $limit
     * @return array
     */
    public function getAll( array $parameters, int $limit): array
    {
        $entitiesNumber = $this->repositoryManager->getRepository(User::class)->getCount();

        $page = $parameters["page"] ?? 1;
        $offset = $limit * ($page - 1);

        $results = $this->repositoryManager->getRepository(User::class)->findBy([], [], $offset, $limit);

        return [
            "listingPage" => self::LISTING_PAGE,
            "users" => $results,
            "page" => $page,
            "entitiesNumber" => $entitiesNumber,
            "limit" => $limit
            ];
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