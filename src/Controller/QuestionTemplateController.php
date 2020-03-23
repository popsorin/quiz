<?php

namespace Quiz\Controller;

use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizTemplateService;

/**
 * Class QuestionTemplateController
 * @package Quiz\Controller
 */
class QuestionTemplateController extends AbstractController
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';
    const QUESTIONS_PER_PAGE = 4;

    /**
     * @var QuizTemplateService
     */
    private $boundedService;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var QuestionTemplateService
     */
    protected $service;

    /**
     * QuestionTemplateController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $boundedService
     * @param SessionInterface $session
     * @param QuestionTemplateService $service
     */
    public function __construct(RendererInterface $renderer, QuestionTemplateService $service, SessionInterface $session,QuizTemplateService $boundedService)
    {
        parent::__construct($renderer);
        $this->boundedService = $boundedService;
        $this->session = $session;
        $this->service = $service;
    }


    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, array $attributes)
    {
        $id = isset($attributes['id']) ? $attributes['id'] : null;
        $this->service->add($id, $request->getParameters());

        return self::createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }


    public function delete(Request $request, array $attributes)
    {
        $this->service->deleteById($attributes["id"]);

        return self::createResponse($request, "301", "Location", ["/dashboard/questions?page="]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
        $props = $this->service->getAll($page, self::QUESTIONS_PER_PAGE, 0 );

        return $this->renderer->renderView(
            $props['listingPage'],
            [
                "questions" => $props['questions'],
                "page" => $props['page'],
                "entitiesNumber" => $props['entitiesNumber'],
                "limit" => $props['limit']
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
        $question = $this->service->questionDetails($request, $attributes);
        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
        $quizzes = $this->boundedService->getAll($page, 0);

        return $this->renderer->renderView(
            "admin-question-details.phtml",
            [
                "question" => $question,
                "quizzes" => $quizzes['quizzes'],
            ]);
    }
}