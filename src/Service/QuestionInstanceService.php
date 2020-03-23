<?php


namespace Quiz\Service;


use Exception;
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

    /**
     * @param EntityInterface $questionInstance
     * @return bool
     */
    public function add(EntityInterface $questionInstance): bool
    {
        /** @var QuestionTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuestionInstance::class);

        return $repository->insertOnDuplicateKeyUpdate($questionInstance);
    }

    /**
     * @param int $quizInstanceId
     * @param int $offset
     * @param int $size
     * @return array
     */
    public function getAll(int $quizInstanceId, int $offset, int $size): array
    {
       return $this->repositoryManager->getRepository(QuestionInstance::class)->findBy(["quiz_instance_id" => $quizInstanceId], [], $offset, $size);
    }

    /**
     * @param int $quizInstanceId
     * @param int $offset
     * @return QuestionInstance
     */
    public function getOne(int $quizInstanceId, int $offset): QuestionInstance
    {
        return $this->repositoryManager->getRepository(QuestionInstance::class)->findBy(["quiz_instance_id" => $quizInstanceId], [], $offset, 1)[0];
    }

    /**
     * @param EntityInterface $questionTemplate
     * @param int $quizInstanceId
     * @param int $questionTemplateId
     * @return QuestionInstance
     */
    public function getQuestionInstance(EntityInterface $questionTemplate, int $quizInstanceId, int $questionTemplateId): QuestionInstance
    {
        $answer = $questionTemplate->getAnswer();
        $text = $questionTemplate->getText();
        $type = $questionTemplate->getType();

        return new QuestionInstance($text, $quizInstanceId, $type, $questionTemplateId, $answer);
    }
}