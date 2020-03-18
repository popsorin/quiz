<?php


namespace Quiz\Service;


use Quiz\Adapter\QuestionTemplateAdapter;
use Quiz\Entity\QuestionInstance;
use Quiz\Entity\QuestionTemplate;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use ReallyOrm\Entity\EntityInterface;
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

    public function add(array $questionTemplate, int $id): bool
    {

        /** @var QuestionTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuestionInstance::class);
        $adaptor = new QuestionTemplateAdapter();
        foreach ($questionTemplate as $key => $value) {
            $quizInstance = $adaptor->getQuestionInstance($value, $id);
            $success = $repository->insertOnDuplicateKeyUpdate($quizInstance);
        }

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