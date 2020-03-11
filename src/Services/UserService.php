<?php


namespace Quiz\Services;


use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\User;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserService extends AbstractService
{

    const LISTING_PAGE = "admin-users-listing.phtml";
    /**
     * UserService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        parent::__construct($repositoryManager);
    }

    /**
     * @param EntityInterface $user
     * @param array $attributes
     * @return bool //maybe make getRepository configurable************************************************
     * //maybe make getRepository configurable*********************************************
     */
    public function add(EntityInterface $user, array $attributes): bool
    {
        $user->setId(isset($attributes['id']) ? $attributes['id'] : null);

        return $this->repositoryManager->getRepository(User::class)->insertOnDuplicateKeyUpdate($user);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @param int $limit
     * @return array
     */
    public function getAll(Request $request, array $attributes, int $limit): array
    {
        $entitiesNumber = $this->repositoryManager->getRepository(User::class)->getCount();

        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
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
     * @param EntityInterface $user
     * @param array $attributes
     * @return bool
     */
    public function delete(EntityInterface $user, array $attributes)
    {
        $user = $this->extractUserId($attributes, User::class);

        return $this->repositoryManager->getRepository(User::class)->delete($user);
    }

}