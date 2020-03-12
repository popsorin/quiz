<?php


namespace Quiz\Service;


use Quiz\Entity\QuestionInstance;
use Quiz\Entity\QuestionTemplate;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionInstanceService
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

    public function add(array $entityData): bool
    {
        $text = isset($entityData['text']) ?  $entityData['text'] : '';
        $answer = isset($entityData['answer']) ? $entityData['answer'] : '';
        $questionId = isset($entityData['questionTemplateId']) ?  $entityData['questionTemplateId'] : '';
        $type = isset($entityData['type']) ?  $entityData['type'] : '';
        $quizId= isset($entityData['quizInstanceId']) ?  $entityData['quizInstanceId'] : '';

        $questionTemplate = new QuestionInstance($text, $quizId, $type, $questionId, $answer);

        /** @var QuestionTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuestionInstance::class);

        $success = $repository->insertOnDuplicateKeyUpdate($questionTemplate);

        // if the question could not be saved, we will not be able to save the associated quizzes
        if (!$success) {
            throw new \Exception("Cannot add question!");
        }

        return $success;
    }

    public function getAll(int $id)
    {
       return $this->repositoryManager->getRepository(QuestionInstance::class)->findBy(["quiz_instance_id" => $id], [], 0, 0);
    }
}