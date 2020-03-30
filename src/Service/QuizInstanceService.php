<?php


namespace Quiz\Service;


use Quiz\Entity\QuizInstance;
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
        return $this->repositoryManager->getRepository(QuizInstance::class)->insertOnDuplicateKeyUpdate($quizInstance);
    }

    /**
     * @param EntityInterface $quizTemplate
     * @param int $quizId
     * @param int $userId
     * @return QuizInstance
     */
    public function makeQuizInstance(EntityInterface $quizTemplate, int $quizId, int $userId): QuizInstance
    {
        $name = $quizTemplate->getName();
        $description = $quizTemplate->getDescription();
        $nrQuestions = $quizTemplate->getNrQuestions();

        return new QuizInstance($quizId, $userId, 0, $name, $description, $nrQuestions);
    }

    /**
     * @param int $quizInstanceId
     * @return int
     */
    public function getNumberOfQuestions(int $quizInstanceId): int
    {
        /** @var  QuizInstance $quizInstance */
        $quizInstance = $this->repositoryManager->getRepository(QuizInstance::class)->findOneBy(["id" => $quizInstanceId]);

        return $quizInstance->getNrQuestions();
    }

    /**
     * @return array|QuizInstance[]
     */
    public function getAll(): array
    {
        return $this->repositoryManager->getRepository(QuizInstance::class)->findBy([], [], 0, 0);
    }
}