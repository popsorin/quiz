<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quiz\Entitie\User;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserController extends AbstractController
{
    /**
     * @var RepositoryManager
     */
    private $repository;

    public function __construct(RendererInterface $renderer, RepositoryInterface $repository)
    {
        parent::__construct($renderer);
        $this->repository = $repository;
    }

    public function add(Request $request, array $attributes) {
        $user = new User(
            $request->getParameter("name"),
            $request->getParameter("password"),
            $request->getParameter("role")
        );

        $this->repository->insertOnDuplicateKeyUpdate($user);
    }
}