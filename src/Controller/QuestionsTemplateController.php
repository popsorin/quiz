<?php

namespace Quiz\Controller;

use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\User;

/**
 * Class QuestionsTemplateController
 * @package Quiz\Controller
 */
class QuestionsTemplateController extends Controller
{
     const LISTING_PAGE = 'admin-questions-listing.phtml';
     const USERS_PER_PAGE = 4;
    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $entitiesNumber = $this->repository->getRepository(QuestionTemplate::class)->getCount();
        $limit = self::USERS_PER_PAGE;

        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
        $offset = $limit * ($page - 1);

        $results = $this->repository->getRepository(QuestionTemplate::class)->findBy([], [], $offset, $limit);
        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "questions" => $results,
                "page" => $page,
                "entitiesNumber" => $entitiesNumber,
                "limit" => $limit
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function questionDetails(Request $request, array $attributes)
    {
        if (!empty($attributes)) {
            $questions = $this->repository->getRepository(QuestionTemplate::class)->find($attributes['id']);
            return $this->renderer->renderView("admin-question-details.phtml", ["question" => $questions]);
        }
        return $this->renderer->renderView("admin-question-details.phtml", ["question" => ""]);
    }
}