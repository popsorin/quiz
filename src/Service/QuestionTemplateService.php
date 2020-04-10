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

    /**
     * @param QuestionTemplate $questionTemplate
     * @return bool
     */
    public function add(QuestionTemplate $questionTemplate): bool
    {
        return $this->repositoryManager->getRepository(QuestionTemplate::class)->insertOnDuplicateKeyUpdate($questionTemplate);
    }

    /**
     * @param QuestionTemplate $questionTemplate
     * @return bool
     */
    public function update(QuestionTemplate $questionTemplate): bool
    {
        return $this->repositoryManager->getRepository(QuestionTemplate::class)->insertOnDuplicateKeyUpdate($questionTemplate);
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
     * @param int $id
     * @return QuestionTemplate
     */
    public function getQuestionDetails(int $id): QuestionTemplate
    {
        return $this->repositoryManager->getRepository(QuestionTemplate::class)->find($id);

    }

    /**
     * @param int $id
     * @return array
     */
    public function getQuestions(int $id): array
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

    /**
     * @param array $filters
     * @return int
     */
    public function getCount(array $filters): int
    {
        return  $this->repositoryManager->getRepository(QuestionTemplate::class)->getCount($filters);
    }
}