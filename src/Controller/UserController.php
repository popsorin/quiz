<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Prophecy\Comparator\Factory;
use Quiz\Entity\User;
use Quiz\Exception\UserAlreadyExistsException;
use Quiz\Factory\UserFactory;
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

    const LISTING_PAGE = "admin-user-details.phtml";

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserService
     */
    private $service;

    /**
     * @var UserFactory
     */
    private $factory;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param UserService $service
     * @param SessionInterface $session
     * @param UserFactory $factory
     */
    public function __construct(
        RendererInterface $renderer,
        UserService $service,
        SessionInterface $session,
        UserFactory $factory
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function add(Request $request, array $attributes): Response
    {
        $entity = $this->factory->createFromRequest($request, "name", "email", "password", "role");
        try {
            $this->service->add($entity);
        } catch (UserAlreadyExistsException $exception) {
            return $this->renderer->renderView(
                self::LISTING_PAGE,
                [
                    "errorMessage" => $exception->getMessage()
                ]
            );
        }
        return $this->reateResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function update(Request $request, array $attributes): Response
    {
        $entity = $this->factory->createFromRequest($request, "name", "email", "password","role");
        $this->service->update($entity);

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
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
     * Returns the page for the add functionality with a set name and a set email
     */
    public function userDetails(Request $request, array $attributes): Response
    {
        $user = $this->service->userDetails($attributes);
        $this->session->start();

        return $this->renderer->renderView(
            "admin-user-details.phtml",
            [
            "action" => "update",
            "name" => $user->getName(),
            "email" => $user->getEmail()
            ]
        );
    }

    /**
     * @return Response
     * Returns the page for the add functionality with the name and email unset
     */
    public function userView(): Response
    {
        return $this->renderer->renderView("admin-user-details.phtml", []);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->service->delete($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
    }
}