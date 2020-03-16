<?php


namespace Quiz\Service\Exception;


use Quiz\Entity\TextAnswerInstance;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class CandidateService
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * CandidateService constructor.
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function add(EntityInterface $entity)
    {
        return $this->repositoryManager->getRepository(TextAnswerInstance::class)->insertOnDuplicateKeyUpdate($entity);
    }
}