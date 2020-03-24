<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Exception\UserAlreadyExistsException;
use Quiz\Factory\UserFactory;
use Quiz\Service\PaginatorService;
use Quiz\Service\UserService;

class UserController extends AbstractController
{
    const USERS_PER_PAGE = 4;

    const PAGE_DETAILS = "admin-user-details.phtml";

    const PAGE_LISTING = "admin-users-listing.phtml";

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
    )
    {
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
                self::PAGE_DETAILS,
                [
                    "errorMessage" => $exception->getMessage()
                ]
            );
        }

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
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
        $numberOfUsers = $this->service->getCount();
        $properties = $request->getParameters();
        $currentPage = (isset($properties["page"])) ? $properties["page"] : 1;
        $paginator = new PaginatorService($numberOfUsers, $currentPage);

        $users = $this->service->getAll($paginator->getResultsPerPage(), $currentPage);


        return $this->renderer->renderView(
            self::PAGE_LISTING ,
            [
                "users" => $users,
                "paginator" => $paginator
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