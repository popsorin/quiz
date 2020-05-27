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
use Quiz\Service\Exception\WrongPasswordException;
use Quiz\Service\LoginService;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class LoginController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var LoginService
     */
    private $loginService;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * LoginController constructor.
     * @param RendererInterface $renderer
     * @param LoginService $service
     * @param SessionInterface $session
     * @param UserFactory $userFactory
     */
    public function __construct(
        RendererInterface $renderer,
        LoginService $service,
        SessionInterface $session,
        UserFactory $userFactory
    ) {
        parent::__construct($renderer);
        $this->loginService = $service;
        $this->session = $session;
        $this->userFactory = $userFactory;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function displayLogin(Request $request, array $attributes): Response
    {
        $this->session->start();
        $user = $this->session->get("user");
        if (!$user) {
            return $this->renderer->renderView("login.html", $attributes);
        }


        if($this->session->get("role")=== "admin") {

            return $this->createResponse($request, "301", "Location", ["/dashboard"]);
        }

        return $this->createResponse($request, "301", "Location", ["/homepage"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws Exception
     */
    public function login(Request $request, array $attributes): Response
    {
        $this->session->start();
        $user = $this->userFactory->createFromRequest($request);
        try {

            $entity = $this->loginService->login(["email" => $user->getEmail(), "password" => $user->getPassword()]);
        }
        catch (WrongPasswordException $exception) {
            return $this->createResponse($request, "301", "Location", ["/"]);
        }

        $this->session->set("user", $entity);
        
        if($entity->getRole() === "admin") {

            return $this->createResponse($request, "301", "Location", ["/dashboard"]);
        }

        return $this->createResponse($request, "301", "Location", ["/homepage"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function logout(Request $request, array $attributes): Response
    {
        $this->session->start();
        $user = $this->session->get("user");
        if($user){
            $this->session->destroy();
        }

        return $this->createResponse($request, "301", "Location", ["/"]);

    }
}