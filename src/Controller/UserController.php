<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Service\AbstractService;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReflectionClass;
use ReflectionException;

class UserController extends Controller
{
    const USERS_PER_PAGE = 4;
    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param AbstractService $service
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        AbstractService $service,
        SessionInterface $session
    )
    {
        parent::__construct($renderer, $service, $session);
    }

    /**
    * @param Request $request
    * @param string $className
    * @return EntityInterface|null
    */
    public function extractUser(Request $request, string $className): ?EntityInterface
    {
        return new $className($request->getParameter("name"), $request->getParameter("password"), $request->getParameter("role"));
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