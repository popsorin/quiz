<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Factory\UserFactory;
use Quiz\Service\Exception\EmailAlreadyTakenException;
use Quiz\Service\Exception\UserAlreadyExistsException;
use Quiz\Service\Exception\UserNotFountException;
use Quiz\Service\PaginatorService;
use Quiz\Service\UserService;
use Quiz\Service\Validator\EntityValidator;

class UserController extends AbstractController
{
    const USERS_PER_PAGE = 4;

    const ADMIN_USER_DETAILS_PAGE  = "admin-user-details.phtml";

    const ADMIN_USER_LISTING_PAGE = "admin-users-listing.phtml";

    const EXCEPTIONS_PAGE = "exceptions-page.html";

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
     * @var EntityValidator
     */
    private $validator;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param UserService $service
     * @param SessionInterface $session
     * @param UserFactory $factory
     * @param EntityValidator $validator
     */
    public function __construct(
        RendererInterface $renderer,
        UserService $service,
        SessionInterface $session,
        UserFactory $factory,
        EntityValidator $validator
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
        $this->factory = $factory;
        $this->validator = $validator;
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
                self::ADMIN_USER_DETAILS_PAGE,
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
        $id = $attributes["id"];
        $updatedUser = $this->factory->createFromRequest($request, "name", "email", "password","role");
        $updatedUser->setId($id);

        try {
            $this->validator->validate($updatedUser);
        }
        catch (UserNotFountException $exception) {
            return $this->renderer->renderView(self::EXCEPTIONS_PAGE, []);
        }
        catch (EmailAlreadyTakenException $exception) {
            return $this->renderer->renderView(
                "admin-user-details.phtml",
                [
                    "role" => $exception->getEntity()->getRole(),
                    "name" => $exception->getEntity()->getName(),
                    "email" => $exception->getEntity()->getEmail(),
                    "errorMessage" => $exception->getMessage()
                ]
            );
        }
        $this->service->update($updatedUser);

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

        $parameters = $request->getParameters();
        $currentPage = $parameters["page"] ?? 1;
        $paginator = new PaginatorService($numberOfUsers, $currentPage);

        $users = $this->service->getAll($paginator->getResultsPerPage(), $currentPage);

        return $this->renderer->renderView(
            self::ADMIN_USER_LISTING_PAGE ,
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
     * Returns the page for the update functionality
     */
    public function showUserEditPage(Request $request, array $attributes): Response
    {
        $this->session->start();
        $user = $this->service->findUserById($attributes["id"]);

        return $this->renderer->renderView(
            "admin-user-details.phtml",
            [
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "userRole" => $user->getRole()
            ]
        );
    }

    /**
     * @return Response
     * Returns the page for the add functionality
     */
    public function showUserAddPage(): Response
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