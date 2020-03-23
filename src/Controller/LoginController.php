<?php


namespace Quiz\Controller;


use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Session;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Service\Exception\WrongPasswordException;
use Quiz\Service\LoginService;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class LoginController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var LoginService
     */
    protected $service;

    /**
     * LoginController constructor.
     * @param RendererInterface $renderer
     * @param LoginService $service
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        LoginService $service,
        SessionInterface $session
    ) {
        parent::__construct($renderer);
        $this->service = $service;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function displayLogin(Request $request, array $attributes): Response
    {
        $this->session->start();
        if ($this->session->get("name") === null) {
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
        try {
            $entity = $this->service->login(["name" => $request->getParameter("name"), "password" => $request->getParameter("password")]);
        }
        catch (WrongPasswordException $exception) {
            return $this->createResponse($request, "301", "Location", ["/"]);
        }
        $this->session->set("name", $entity->getName());
        $this->session->set("role", $entity->getRole());
        $this->session->set("id", $entity->getId());

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
        if($this->session->get("name")) {
            $this->session->destroy();
        }

        return $this->createResponse($request, "301", "Location", ["/"]);

    }
}