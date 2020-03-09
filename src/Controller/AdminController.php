<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Response;
use ReallyOrm\Test\Repository\RepositoryManager;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     * @param RendererInterface $renderer
     * @param RepositoryManager $repository
     * @param SessionInterface $session
     */
    public function __construct(RendererInterface $renderer, RepositoryManager $repository, SessionInterface $session)
    {
        parent::__construct($renderer, $repository, $session);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showDashBoard(Request $request, array $attributes)
    {
        $this->session->start();
        if(($this->session->get("name")) === null) {
            return self::createResponse($request, "301", "Location", ["/"]);
        }
            return $this->renderer->renderView("admin-dashboard.html", $attributes);

    }
}