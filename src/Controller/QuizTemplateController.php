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

class QuizTemplateController extends AbstractController
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';
    const QUESTIONS_PER_PAGE = 4;

    /**
     * @var QuestionTemplateService
     */
    private $boundedService;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var QuizTemplateService
     */
    protected $service;

    /**
     * QuizTemplateController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $service
     * @param SessionInterface $session
     * @param QuestionTemplateService $boundedService
     */
    public function __construct(RendererInterface $renderer,QuizTemplateService $service, SessionInterface $session, QuestionTemplateService $boundedService)
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
    public function add(Request $request, array $attributes): Response
    {
        $this->session->start();
        $updateId = isset($attributes['id']) ?? null;
        $this->service->add($updateId, $this->session->get("id"), $request->getParameters());

        return $this->createResponse($request, "301", "Location", ["/dashboard/quizzes"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->service->deleteById($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/quizzes"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $page = $request->getParameter("page") ?? 1;
        $props = $this->service->getAll($page, self::QUESTIONS_PER_PAGE);

        return $this->renderer->renderView(
            $props['listingPage'],
            [
                "quizzes" => $props['quizzes'],
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
     * Returns the page for the edit functionality for the quizzes
     */
    public function getQuizDetails(Request $request, array $attributes): Response
    {
        $quiz = $this->service->quizDetails($attributes);
        $page = $request->getParameter("page") ?? 1;

        $id = (isset($attributes['id']) ?? 0);
        $questions = $this->boundedService->getAll($page, 0, $id);

        return $this->renderer->renderView(
            "admin-quiz-details.phtml",
            [
                "quiz" => $quiz,
                "questions" => $questions['questions'],
                'questionIds' => $questions['questionIds']
            ]);
    }
}