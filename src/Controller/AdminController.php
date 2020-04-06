<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;

class AdminController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * AdminController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showDashboard(Request $request, array $attributes): Response
    {
        $this->session->start();
        if(($this->session->get("email")) === null) {
            return $this->createResponse($request, "301", "Location", ["/"]);
        }

        return $this->renderer->renderView("admin-dashboard.html", $attributes);
    }
}