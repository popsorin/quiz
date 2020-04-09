<?php

namespace Quiz\Controller;

use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\User;
use Quiz\Factory\UserFactory;
use Quiz\Service\Exception\InvalidUserException;
use Quiz\Service\PaginatorService;
use Quiz\Service\UserService;
use Quiz\Service\Validator\EntityValidatorInterface;

class UserController extends AbstractController
{
    const USERS_PER_PAGE = 4;

    const ADMIN_USER_DETAILS_PAGE  = "admin-user-details.phtml";

    const ADMIN_USER_LISTING_PAGE = "admin-users-listing.phtml";

    const EXCEPTIONS_PAGE_TEMPLATE = "exceptions-page.html";

    const USER_ROLE_TYPES = ["admin", "candidate"];

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var EntityValidatorInterface
     */
    private $userValidator;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param UserService $service
     * @param SessionInterface $session
     * @param UserFactory $factory
     * @param EntityValidatorInterface $validator
     */
    public function __construct(
        RendererInterface $renderer,
        UserService $service,
        SessionInterface $session,
        UserFactory $factory,
        EntityValidatorInterface $validator
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->userService = $service;
        $this->userFactory = $factory;
        $this->userValidator = $validator;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function add(Request $request, array $attributes): Response
    {
        $user = $this->userFactory->createFromRequest($request, "name", "email", "password", "role");

        try {
            $this->userValidator->validate($user);
        } catch (InvalidUserException $exception) {
            $message = $this->buildExceptionMessage($exception);

            return $this->renderer->renderView(
                "admin-user-details.phtml",
                [
                    "user" => $user,
                    "errorMessage" => $message
                ]
            );
        }

        $this->userService->add($user);

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     *
     *
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function update(Request $request, array $attributes): Response
    {
        $id = $attributes["id"];
        $updatedUser = $this->userFactory->createFromRequest($request, "name", "email", "password","role");
        $updatedUser->setId($id);

        try {
            $this->userValidator->validate($updatedUser);
        } catch (InvalidUserException $exception) {
            $message = $this->buildExceptionMessage($exception);

            return $this->renderer->renderView(
                "admin-user-details.phtml",
                [
                    "user" => $updatedUser,
                    "errorMessage" => $message
                ]
            );
        }

        $this->userService->update($updatedUser);

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $numberOfUsers = $this->userService->getCount();

        $parameters = $request->getParameters();
        $currentPage = $parameters["page"] ?? 1;
        $paginator = new PaginatorService($numberOfUsers, $currentPage);

        $users = $this->userService->getAll($paginator->getResultsPerPage(), $currentPage);

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
    public function showEditUserPage(Request $request, array $attributes): Response
    {
        $user = $this->userService->findUserById($attributes["id"]);

        return $this->renderer->renderView(
            "admin-user-details.phtml",
            [
                "user" => $user,
                "roles" => self::USER_ROLE_TYPES
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showNewUserPage(Request $request, array $attributes): Response
    {
        $user = new User("","","","");
        return $this->renderer->renderView(
            "admin-user-details.phtml",
            [
                "user" => $user,
                "roles" => self::USER_ROLE_TYPES
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->userService->delete($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     *
     * Concatenates the error messages then returns them
     *
     * In case there is no error found it returns empty string
     *
     * @param Exception $exception
     * @return string
     */
    private function buildExceptionMessage(Exception $exception): string
    {
        $message = "";

        while ($exception->getPrevious() !== null) {
            $message .= $exception->getMessage() . '<br>';
            $exception = $exception->getPrevious();
        }

        return $message;
    }
}