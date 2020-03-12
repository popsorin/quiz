<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Service\UserService;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReflectionClass;
use ReflectionException;

class UserController extends AbstractController
{
    const USERS_PER_PAGE = 4;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var UserService
     */
    protected $service;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     * @param UserService $service
     */
    public function __construct(RendererInterface $renderer,UserService $service, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * //maybe make getRepository configurable************************************************
     */
    public function add(Request $request, array $attributes)
    {
        $id = isset($attributes['id']) ? $attributes['id'] : null;
        $this->service->add($id, $request->getParameters());

        return self::createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $props = $this->service->getAll($request, $attributes, self::USERS_PER_PAGE);
        return $this->renderer->renderView(
            $props['listingPage'],
            [
                "users" => $props['users'],
                "page" => $props['page'],
                "entitiesNumber" => $props['entitiesNumber'],
                "limit" => $props['limit']
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
        $user = $this->service->userDetails($attributes);

        return $this->renderer->renderView("admin-user-details.phtml", ["user" => $user]);
    }

    public function delete(Request $request, array $attributes)
    {
        $this->service->delete($attributes["id"]);

        return self::createResponse($request, "301", "Location", ["/dashboard/users"]);
    }
}