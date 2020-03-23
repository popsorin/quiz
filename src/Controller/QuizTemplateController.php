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
    public function add(Request $request, array $attributes)
    {
        $this->session->start();
        $updateId = isset($attributes['id']) ? $attributes['id'] : null;
        $this->service->add($updateId, $this->session->get("id"), $request->getParameters());

        return self::createResponse($request, "301", "Location", ["/dashboard/quizzes"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes)
    {
        $this->service->deleteById($attributes["id"]);

        return self::createResponse($request, "301", "Location", ["/dashboard/quizzes?page="]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
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
     * Returns the page for the add functionality with a set name and a set description
     */
    public function quizDetails(Request $request, array $attributes)
    {
        $quiz = $this->service->quizDetails($attributes);
        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");

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