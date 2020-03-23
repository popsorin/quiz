<?php


namespace Quiz\Service;


use Quiz\Entity\QuestionInstance;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\QuizInstance;
use Quiz\Entity\QuizTemplate;
use Quiz\Entity\QuizTemplateInterface;
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
    public function add(EntityInterface $quizInstance): bool
    {
        $repository =  $this->repositoryManager->getRepository(QuizInstance::class);

        return $repository->insertOnDuplicateKeyUpdate($quizInstance);
    }

    /**
     * @param EntityInterface $quizTemplate
     * @param int $quizId
     * @param int $userId
     * @return QuizInstance
     */
    public function makeQuizInstance(EntityInterface $quizTemplate, int $quizId, int $userId) :QuizInstance
    {
        $name = $quizTemplate->getName();
        $description = $quizTemplate->getDescription();
        $nrQuestions = $quizTemplate->getNrQuestions();

        return new QuizInstance($quizId, $userId, 0, $name, $description, $nrQuestions);
    }
}