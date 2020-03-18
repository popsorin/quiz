<?php


namespace Quiz\Service;


use Quiz\Entity\QuestionInstance;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\QuizInstance;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use ReallyOrm\Entity\AbstractEntity;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizInstanceService
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * QuestionInstanceService constructor.
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param EntityInterface $quizInstance
     * @return bool
     */
    public function add(EntityInterface $quizInstance): ?bool
    {
        $repository =  $this->repositoryManager->getRepository(QuizInstance::class);

        return $repository->insertOnDuplicateKeyUpdate($quizInstance);
    }
}