<?php


namespace Quiz\Service;


use Quiz\Entity\QuizTemplate;
use Quiz\Exception\QuizTemplateAlreadyExistsException;
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
     * @param EntityInterface $quizTemplate
     * @param array $questionsIds
     * @return bool
     */
    public function add(EntityInterface $quizTemplate, array $questionsIds): bool
    {
        $repository =  $this->repositoryManager->getRepository(QuizTemplate::class);

        $success = $repository->insertOnDuplicateKeyUpdate($quizTemplate);
        foreach ($questionsIds as $questionsId) {
            if ($success === false) {
                return $success;
            }
            $success = $repository->insertIntoLinkTable($questionsId, $quizTemplate->getId());
        }

        return $success;
    }

    /**
     * @param EntityInterface $quizTemplate
     * @param array $questionsIds
     * @return bool
     */
    public function update(EntityInterface $quizTemplate, array $questionsIds): bool
    {
        $repository = $this->repositoryManager->getRepository(QuizTemplate::class);
        $success = $repository->deleteQuestionsFromLinkedTable($quizTemplate->getId());

        foreach ($questionsIds as $questionsId) {
            if ($success === false) {
                return $success;
            }
            $success = $repository->insertIntoLinkTable($questionsId, $quizTemplate->getId());
        }
        $success = $repository->insertOnDuplicateKeyUpdate($quizTemplate);
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

        return $this->repositoryManager->getRepository(QuizTemplate::class)->findBy([], [], $offset, $limit);
    }

    /**
     * @param int $quizId
     * @return QuizTemplate
     */
    public function getQuizDetails(int $quizId): QuizTemplate
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->find($quizId);
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
     * @return QuizTemplate
     */
    public function getOneQuiz(array $filters): QuizTemplate
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->findOneBy($filters);
    }

    /**
     * @param array $filters
     * @return int
     */
    public function getCount(array $filters): int
    {
        return  $this->repositoryManager->getRepository(QuizTemplate::class)->getCount($filters);
    }
}