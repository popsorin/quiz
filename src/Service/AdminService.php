<?php


namespace Quiz\Service;


use Framework\Http\Request;
use Framework\Http\Response;
use ReallyOrm\Test\Repository\RepositoryManager;

class AdminService extends AbstractService
{
    /**
     * AdminService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        parent::__construct($repositoryManager);
    }
}