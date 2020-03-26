<?php


namespace Quiz\Service;


use Exception;
use Framework\Http\Request;
use Quiz\Entity\QuestionTemplate;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class QuestionTemplateService
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * QuestionTemplateService constructor.
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function add(?int $entityId, array $entityData): bool
    {
        $answer = isset($entityData['answer']) ?? '';
        $question = isset($entityData['question']) ?? '';
        $type = isset($entityData['type']) ?? '';

        $questionTemplate = new QuestionTemplate($answer, $question, $type);
        $questionTemplate->setId($entityId);

        /** @var QuestionTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuestionTemplate::class);

        $success = $repository->insertOnDuplicateKeyUpdate($questionTemplate);

        // if the question could not be saved, we will not be able to save the associated quizzes
        if (!$success) {
            throw new Exception("Cannot add question!");
        }

        // save associated quizzes one by one
        // note: this should happen in a transaction (in a very very far away future iteration)
        // alternative: use array of quiz IDs in a single insert statement
        if (isset($entityData['quizzes'])) {
            foreach ($entityData['quizzes'] as $quiz) {
                $success = $repository->insertIntoLinkTable($questionTemplate->getId(), $quiz);
            }
        }

        return $success;
    }

    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getAll(int $limit, int $page): array
    {
        $offset = $limit * ($page - 1);

        return $this->repositoryManager->getRepository(QuestionTemplate::class)->findBy([], [], $offset, $limit);
    }

    /**
     * @param int $quizTemplateId
     * @return array
     */
    public function getAllQuestionIdsFromOneQuiz(int $quizTemplateId): array
    {
        return  $this->repositoryManager->getRepository(QuestionTemplate::class)->getQuestionIds($quizTemplateId);
    }

    /**
     * @param array $parameter
     * @return array
     */
    public function getAllFiltered(array $parameter): array
    {
        $result = [];
        foreach ($parameter as $param) {
            $aux = ["id" => $param];
            $result = array_merge($result, $this->repositoryManager->getRepository(QuestionTemplate::class)->findBy($aux, [], 0, 0));
        }

        return $result;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->repositoryManager->getRepository(QuestionTemplate::class)->deleteById($id);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return mixed
     */
    public function questionDetails(Request $request, array $attributes)
    {
        if (!empty($attributes)) {
            return $this->repositoryManager->getRepository(QuestionTemplate::class)->find($attributes['id']);
        }

        return "";
    }

    /**
     * @param int|null $id
     * @return array
     */
    public function getQuestions(?int $id): array
    {
        /** @var QuestionTemplateRepository $repository*/
        $repository =$this->repositoryManager->getRepository(QuestionTemplate::class);
        $questionTemplateId = $repository->getQuestionIds($id);
        $questionTemplate = [];
        foreach ($questionTemplateId as $questionId) {
            $questionTemplate =array_merge($questionTemplate,  $repository->findBy(["id" => $questionId], [], 0, 0));
        }
        return $questionTemplate;
    }
}