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
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Service\Exception\InvalidUserException;
use Quiz\Service\ParameterBag;
use Quiz\Service\RequestParameterBag;
use Quiz\Service\Paginator;
use Quiz\Service\URLHelper;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var EntityValidatorInterface
     */
    private $userValidator;

    /**
     * @var URLHelper
     */
    private $urlHelper;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param UserRepository $service
     * @param SessionInterface $session
     * @param UserFactory $factory
     * @param EntityValidatorInterface $validator
     * @param URLHelper $urlHelper
     */
    public function __construct(
        RendererInterface $renderer,
        UserRepository $service,
        SessionInterface $session,
        UserFactory $factory,
        EntityValidatorInterface $validator,
        URLHelper $urlHelper
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->userRepository = $service;
        $this->userFactory = $factory;
        $this->userValidator = $validator;
        $this->urlHelper = $urlHelper;
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
            $errorMessage = $this->buildExceptionMessage($exception);

            return $this->renderer->renderView(
                "admin-user-details.phtml",
                [
                    "user" => $user,
                    "errorMessage" => $errorMessage,
                    "roles" => self::USER_ROLE_TYPES
                ]
            );
        }

        $this->userRepository->insertOnDuplicateKeyUpdate($user);

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
        $updatedUser = $this->userFactory->createFromRequest($request, "name", "email", "password","role");
        $updatedUser->setId($id);

        try {
            $this->userValidator->validate($updatedUser);
        } catch (InvalidUserException $exception) {
            $errorMessage = $this->buildExceptionMessage($exception);

            return $this->renderer->renderView(
                "admin-user-details.phtml",
                [
                    "user" => $updatedUser,
                    "errorMessage" => $errorMessage,
                    "roles" => self::USER_ROLE_TYPES
                ]
            );
        }

        $this->userRepository->insertOnDuplicateKeyUpdate($updatedUser);

        return $this->createResponse($request, "301", "Location", ["/dashboard/users"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $requestParameterBag = new RequestParameterBag($request->getParameters());
        $currentPage = $request->getParameter("page") ?? 1;
        $numberOfUsers = $this->userRepository->getCount($requestParameterBag->getFilterParameters());
        $paginator = new Paginator($numberOfUsers, $currentPage);

        $users = $this->userRepository->findBy(
            array_merge($requestParameterBag->getFilterParameters(), $requestParameterBag->getSearchParameters()),
            $requestParameterBag->getSortParameters(),
            $paginator->getResultsPerPage() * ($currentPage - 1),
            $paginator->getResultsPerPage()
        );


        return $this->renderer->renderView(
            self::ADMIN_USER_LISTING_PAGE ,
            [
                "users" => $users,
                "paginator" => $paginator,
                "roles" => self::USER_ROLE_TYPES,
                "requestParameterBag" => $requestParameterBag,
                "urlHelper" => $this->urlHelper
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
        $user = $this->userRepository->find($attributes["id"]);

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
        $this->userRepository->deleteById($attributes["id"]);

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
        $errorMessage = "";

        while ($exception !== null) {
            $errorMessage .= $exception->getMessage() . '<br>';
            $exception = $exception->getPrevious();
        }

        return $errorMessage;
    }
}