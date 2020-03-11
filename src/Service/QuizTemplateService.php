<?php


namespace Quiz\Service;


use Framework\Http\Request;
use Quiz\Entity\QuizTemplate;
use ReallyOrm\Test\Repository\RepositoryManager;

class QuizTemplateService extends AbstractService
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';

    /**
     * QuizTemplateService constructor.
     * @param RepositoryManager $repositoryManager
     */
    public function __construct(RepositoryManager $repositoryManager)
    {
        parent::__construct($repositoryManager);
    }

    public function getAll(Request $request, array $attributes, int $limit): array
    {
        $entitiesNumber = $this->repositoryManager->getRepository(QuizTemplate::class)->getCount();

        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
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
}