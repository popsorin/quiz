<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReflectionClass;
use ReflectionException;

class UserController extends Controller
{
    const USERS_PER_PAGE = 4;
    const LISTING_PAGE = "admin-users-listing.phtml";
    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param RepositoryManager $repository
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        RepositoryManager $repository,
        SessionInterface $session
    )
    {
        parent::__construct($renderer, $repository, $session);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * //maybe make getRepository configurable************************************************
     */
    public function add(Request $request, array $attributes)
    {
        $user = $this->extractUser($request, User::class);
        $user->setId(isset($attributes['id']) ? $attributes['id'] : null);
        $this->repository->getRepository(User::class)->insertOnDuplicateKeyUpdate($user);
        return self::createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $entitiesNumber = $this->repository->getRepository(User::class)->getCount();
        $limit = self::USERS_PER_PAGE;

        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
        $offset = $limit * ($page - 1);

        $results = $this->repository->getRepository(User::class)->findBy([], [], $offset, $limit);
        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "users" => $results,
                "page" => $page,
                "entitiesNumber" => $entitiesNumber,
                "limit" => $limit
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function userDetails(Request $request, array $attributes)
    {
        if (!empty($attributes)) {
            $user = $this->repository->getRepository(User::class)->find($attributes['id']);
            return $this->renderer->renderView("admin-user-details.phtml", ["user" => $user]);
        }
        return $this->renderer->renderView("admin-user-details.phtml", ["user" => ""]);
    }

    public function delete(Request $request, array $attributes)
    {
        $user = $this->extractUserId($attributes, User::class);
        $this->repository->getRepository(User::class)->delete($user);
        return self::createResponse($request, "301", "Location", ["/dashboard/users"]);
    }
}