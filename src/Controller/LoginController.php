<?php


namespace Quiz\Controller;


use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Session;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Services\AbstractService;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class LoginController extends Controller
{
    /**
     * LoginController constructor.
     * @param RendererInterface $renderer
     * @param AbstractService $service
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        AbstractService $service,
        SessionInterface $session
    ) {
        parent::__construct($renderer,$service, $session);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function displayLogin(Request $request, array $attributes)
    {
        $this->session->start();
        if ($this->session->get("name") === null) {
            return $this->renderer->renderView("login.html", $attributes);
        }


        return self::createResponse($request, "301", "Location", ["dashboard"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws Exception
     */
    public function login(Request $request, array $attributes)
    {
        $this->session->start();
        $credentials = self::extractArray($request);
        $entity = $this->service->login($credentials);
        $this->session->set("name", $entity->getName());

        if($entity->getRole() === "admin") {

            return self::createResponse($request, "301", "Location", ["dashboard"]);
        }

        return self::createResponse($request, "301", "Location", ["homepage"]);
    }

    public function logout(Request $request, array $attributes)
    {
        $this->session->start();
        if($this->session->get("name")){
            $this->session->destroy();
            return self::createResponse($request, "301", "Location", ["/"]);
        }
    }
}