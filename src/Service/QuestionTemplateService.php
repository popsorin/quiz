<?php


namespace Quiz\Service;


use Framework\Http\Request;
use Quiz\Entity\QuestionTemplate;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class QuestionTemplateService extends AbstractService
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';

    /**
     * QuestionTemplateService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        parent::__construct($repositoryManager);
    }

    /**
     * @param int $idQuestion
     * @param int $idQuiz
     */
    public function insertIntoLinkTable(int $idQuestion, int $idQuiz)
    {
        $this->repositoryManager->getRepository(QuestionTemplate::class)->insertIntoLinkTable($idQuestion, $idQuestion, "quiz_question_template");
    }


    public function add(?int $entityId, array $entityData): bool
    {
        $answer = isset($entityData['answer']) ? $entityData['answer'] : '';
        $question = isset($entityData['question']) ?  $entityData['question'] : '';
        $type = isset($entityData['type']) ?  $entityData['type'] : '';

        $questionTemplate = new QuestionTemplate($answer, $question, $type);
        $questionTemplate->setId($entityId);

        /** @var QuestionTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuestionTemplate::class);

        $success = $repository->insertOnDuplicateKeyUpdate($questionTemplate);

        // if the question could not be saved, we will not be able to save the associated quizzes
        if (!$success) {
            throw new \Exception("Cannot add question!");
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
     * @param Request $request
     * @param array $attributes
     * @param int $limit
     * @return array
     */
    public function getAll(Request $request, array $attributes, int $limit): array
    {
        $entitiesNumber = $this->repositoryManager->getRepository(QuestionTemplate::class)->getCount();

        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
        $offset = $limit * ($page - 1);

        $results = $this->repositoryManager->getRepository(QuestionTemplate::class)->findBy([], [], $offset, $limit);

        return [
            "listingPage" => self::LISTING_PAGE,
            "questions" => $results,
            "page" => $page,
            "entitiesNumber" => $entitiesNumber,
            "limit" => $limit
        ];
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

}