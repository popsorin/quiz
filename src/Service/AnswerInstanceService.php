<?php


namespace Quiz\Service;


use Quiz\Entity\AnswerChoiceInstance;
use Quiz\Entity\AnswerTextInstance;
use Quiz\Persistency\Repositories\AnswerChoiceInstanceRepository;
use ReallyOrm\Entity\EntityInterface;
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
     * @param EntityInterface $questionInstance
     * @return bool
     */
    public function add(EntityInterface $answerInstance, EntityInterface $questionInstance): bool
    {
        ($questionInstance->getType() === "choice") ?
        /** @var AnswerChoiceInstanceRepository $repository */
        $repository = $this->repositoryManager->getRepository(AnswerChoiceInstance::class)  :
        /** @var AnswerChoiceInstanceRepository $repository */
        $repository = $this->repositoryManager->getRepository(AnswerTextInstance::class);


        return $repository->insertOnDuplicateKeyUpdate($answerInstance);
    }

    /**
     * @param array $questions
     * @return array
     */
    public function getAll(array $questions): array
    {
        $answers = [];
        /** @var AnswerChoiceInstanceRepository $repository */
        $repositoryChoice = $this->repositoryManager->getRepository(AnswerChoiceInstance::class);
        /** @var AnswerChoiceInstanceRepository $repository */
        $repositoryText = $this->repositoryManager->getRepository(AnswerTextInstance::class);
        foreach ($questions as $question) {
            ($question->getType() === "text") ? $answer = $repositoryText->findOneBy(["question_instance_id" => $question->getId()]) :
                                                $answer = $repositoryChoice->findOneBy(["question_instance_id" => $question->getId()]);
            $answers[] = $answer;
        }

        return $answers;
    }
}