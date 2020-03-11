<?php


namespace Quiz\Services;


use Framework\Http\Request;
use Quiz\Entity\QuestionTemplate;
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

    /**
     * @param QuestionTemplate $questionTemplate
     * @param array $attributes
     * @return bool
     */
    public function add(QuestionTemplate $questionTemplate, array $attributes): bool
    {

        $questionTemplate->setId(isset($attributes['id']) ? $attributes['id'] : null);

        return $this->repositoryManager->getRepository(QuestionTemplate::class)->insertOnDuplicateKeyUpdate($questionTemplate);
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