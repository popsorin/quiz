<?php


namespace Quiz\Service;


use Framework\Http\Request;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\QuizTemplate;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\QuizTemplateRepository;
use Quiz\Persistency\Repositories\UserRepository;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class QuizTemplateService
{
    const LISTING_PAGE = 'admin-quizzes-listing.phtml';

    /**
     * @var RepositoryManagerInterface
     */
    private $repositoryManager;

    /**
     * QuizTemplateService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
       $this->repositoryManager = $repositoryManager;
    }

    /**
     * @param int|null $updateId
     * @param int|null $userId
     * @param array $entityData
     * @return bool //maybe make getRepository configurable************************************************
     * //maybe make getRepository configurable*******************************************
     * @throws \Exception
     */
    public function add(?int $updateId, ?int $userId, array $entityData): bool
    {
        $name = isset($entityData['name']) ? $entityData['name'] : '';
        $description = isset($entityData['description']) ?  $entityData['description'] : '';

        $quizTemplate = new QuizTemplate($userId, $name, $description, count($entityData["questions"]));
        $quizTemplate->setId($updateId);

        /** @var QuizTemplateRepository $repository */
        $repository =  $this->repositoryManager->getRepository(QuizTemplate::class);

        $success = $repository->insertOnDuplicateKeyUpdate($quizTemplate);

        // if the question could not be saved, we will not be able to save the associated quizzes
        if (!$success) {
            throw new \Exception("Cannot add quiz!");
        }

        // save associated quizzes one by one
        // note: this should happen in a transaction (in a very very far away future iteration)
        // alternative: use array of quiz IDs in a single insert statement
        if (isset($entityData['questions'])) {
            $repository->deleteQuestions($quizTemplate->getId());
            foreach ($entityData['questions'] as $question) {
                $success = $repository->insertIntoLinkTable($question, $quizTemplate->getId());
            }
        }

        return $success;
    }

    /**
     * @param string $parameter
     * @param int $limit
     * @return array
     */
    public function getAll(string $parameter, int $limit): array
    {
        $entitiesNumber = $this->repositoryManager->getRepository(QuizTemplate::class)->getCount();

        $page = $parameter;
        $offset = $limit * ($page - 1);

        $results = $this->repositoryManager->getRepository(QuizTemplate::class)->findBy([], [], $offset, $limit);

        return [
            "listingPage" => self::LISTING_PAGE,
            "quizzes" => $results,
            "page" => $page,
            "entitiesNumber" => $entitiesNumber,
            "limit" => $limit
        ];
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function quizDetails(array $attributes)
    {
        if (!empty($attributes)) {
            return $this->repositoryManager->getRepository(QuizTemplate::class)->find($attributes['id']);
        }

        return "";
    }


    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->deleteById($id);
    }

    /**
     * @param array $filters
     * @return EntityInterface|null
     */
    public function getOneQuiz(array $filters): ?EntityInterface
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->findOneBy($filters);
    }
}