<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Services\AbstractService;
use ReallyOrm\Test\Repository\RepositoryManager;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     * @param RendererInterface $renderer
     * @param AbstractService $service
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        AbstractService $service,
        SessionInterface $session
    ) {
        parent::__construct($renderer, $service, $session);
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