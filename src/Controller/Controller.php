<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class Controller extends AbstractController
{
    /**
     * @var RepositoryManager
     */
    protected $repository;

    public function __construct(RendererInterface $renderer, RepositoryInterface $repository)
    {
        parent::__construct($renderer);
        $this->repository = $repository;
    }
}