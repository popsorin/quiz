<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Http\Request;
use ReallyOrm\Repository\RepositoryInterface;

class LoginController extends Controller
{
    /**
     * LoginController constructor.
     * @param RendererInterface $renderer
     * @param RepositoryInterface $repository
     */
    public function __construct(RendererInterface $renderer, RepositoryInterface $repository)
    {
        parent::__construct($renderer, $repository);
    }

    /**
     * @param Request $request
     * @param array $attributes
     */
    public function login(Request $request, array $attributes)
    {
        return $this->renderer->renderView("resource/login.html", $attributes);
    }
}