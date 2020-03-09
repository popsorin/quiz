<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\UserRepository;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReflectionClass;
use ReflectionException;

class UserController extends Controller
{
    /**
     * UserController constructor.
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
     */
    public function add(Request $request, array $attributes)
    {
        $user = $this->extractUser($request);
        $this->repository->getRepository(User::class)->insertOnDuplicateKeyUpdate($user);
      //  return $this->renderer->renderView(,$attributes);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $results = $this->repository->getRepository(User::class)->findBy([], [], 0,4);

        return $this->renderer->renderView("admin-users-listing.phtml", ["users" =>$results]);
    }

    public function userDetails(Request $request, array $atributes)
    {
        return $this->renderer->renderView("admin-user-details.html", []);
    }
}