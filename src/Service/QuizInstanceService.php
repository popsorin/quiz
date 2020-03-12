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
     * @param int|null $quizId
     * @param EntityInterface $entityData
     * @return bool
     */
    public function add(?int $quizId, EntityInterface $entityData): ?bool
    {
        $quizInstance = new QuizInstance($quizId, $entityData->getId(), 0, $entityData->getName(), $entityData->getDescription(), 0);
        /** @var QuestionTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuizInstance::class);

        return $repository->insertOnDuplicateKeyUpdate($quizInstance);
    }
}