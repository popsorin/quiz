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
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class LoginController extends Controller
{
    /**
     * LoginController constructor.
     * @param RendererInterface $renderer
     * @param RepositoryManager $repository
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        RepositoryManager $repository,
        SessionInterface $session
    ) {
        parent::__construct($renderer, $repository, $session);
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
        $repository = $this->repository->getRepository(User::class);
        $credentials = self::extractArray($request);
        $entity = $repository->findOneBy([$credentials['name']]);
        if($entity->getPassword() !== $credentials["password"]) {
            //redirect back to the page
            throw new Exception("Wrong password");
        }
        $this->session->set("name", $credentials['name']);
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