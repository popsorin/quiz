<?php


namespace Quiz\Service;


use Quiz\Entity\QuizTemplate;
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
     * @return bool
     */
    public function add(EntityInterface $quizTemplate): bool
    {
        return $this->repositoryManager->getRepository(QuizTemplate::class)->insertOnDuplicateKeyUpdate($quizTemplate);
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