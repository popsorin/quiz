<?php

namespace Quiz\Controller;

use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Service\PaginatorService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizTemplateService;

/**
 * Class QuestionTemplateController
 * @package Quiz\Controller
 */
class QuestionTemplateController extends AbstractController
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';

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
    public function add(Request $request, array $attributes): Response
    {
        $id = $attributes['id'] ?? null;
        $this->service->add($id, $request->getParameters());

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->service->deleteById($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $parameters = $request->getParameters();
        $page = $parameters["page"] ?? 1;
        $numberOfQuestions = $this->service->getCount();
        $paginator = new PaginatorService($numberOfQuestions, $page);
        $questionTemplates = $this->service->getAll($paginator->getResultsPerPage(), $page);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "questions" => $questionTemplates,
                "paginator" => $paginator,
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getQuestionDetails(Request $request, array $attributes): Response
    {
        $question = $this->service->getQuestionDetails($request, $attributes);
        $parameters = $request->getParameters();
        $page = $parameters["page"] ?? 1;
        $quizzes = $this->boundedService->getAll($page, 0);

        return $this->renderer->renderView(
            "admin-question-details.phtml",
            [
                "question" => $question,
                "quizzes" => $quizzes['quizzes'],
            ]);
    }
}