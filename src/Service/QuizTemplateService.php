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
     * @throws QuizTemplateAlreadyExistsException
     */
    public function add(EntityInterface $quizTemplate, array $questionsIds): bool
    {
        $repository =  $this->repositoryManager->getRepository(QuizTemplate::class);

        if($repository->findByWithOrOperator(["name" => $quizTemplate->getName()],[],0, 0)) {
            throw new QuizTemplateAlreadyExistsException($quizTemplate);
        }

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
    public function quizDetails(int $quizId): QuizTemplate
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
     * @return EntityInterface|null
     */
    public function getOneQuiz(array $filters): ?EntityInterface
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->findOneBy($filters);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return  $this->repositoryManager->getRepository(QuizTemplate::class)->getCount();
    }
}