<?php


namespace Quiz\Service;


use Quiz\Entity\AnswerChoiceInstance;
use Quiz\Entity\AnswerTextInstance;
use Quiz\Persistency\Repositories\AnswerChoiceInstanceRepository;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class AnswerInstanceService
{
    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * AnswerInstanceService constructor.
     * @param $repositoryManager
     */
    public function __construct($repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param EntityInterface $answerInstance
     * @param string $questionType
     * @return bool
     */
    public function add(EntityInterface $answerInstance, string $questionType): bool
    {
        $repository = $this->getRepository($questionType);

        return $repository->insertOnDuplicateKeyUpdate($answerInstance);
    }

    /**
     * @param array $questions
     * @return array
     */
    public function getAll(array $questions): array
    {
        $answers = [];
        foreach ($questions as $question) {
            $repository = $this->getRepository($question->getType());
            $answer = $repository->findOneBy(["question_instance_id" => $question->getId()]);
            $answers[] = $answer;
        }

        return $answers;
    }

    /**
     * @param string $questionType
     * @return RepositoryInterface
     */
    private function getRepository(string $questionType): RepositoryInterface
    {
        if($questionType === "choice") {
            return $this->repositoryManager->getRepository(AnswerChoiceInstance::class);
        }

        return $this->repositoryManager->getRepository(AnswerTextInstance::class);
    }
}