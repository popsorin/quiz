<?php


namespace Quiz\Services;


use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

abstract class AbstractService
{
    /**
     * @var RepositoryManager
     */
    protected $repositoryManager;

    /**
     * AbstractService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function getRepositoryManager()
    {
        return $this->repositoryManager;
    }
}