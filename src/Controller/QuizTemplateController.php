<?php

namespace Quiz\Controller;

use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Exception\QuizTemplateAlreadyExistsException;
use Quiz\Factory\QuizTemplateFactory;
use Quiz\Service\PaginatorService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizTemplateService;

class QuizTemplateController extends AbstractController
{
    const PAGE_LISTING = 'admin-quizzes-listing.phtml';
    const PAGE_DETAILS = 'admin-quiz-details.phtml';
    const QUESTIONS_PER_PAGE = 4;

    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuizTemplateService
     */
    private $service;

    /**
     * @var QuizTemplateFactory
     */
    private $quizTemplateFactory;

    /**
     * QuizTemplateController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $service
     * @param SessionInterface $session
     * @param QuestionTemplateService $questionTemplateService
     * @param QuizTemplateFactory $quizTemplateFactory
     */
    public function __construct(
        RendererInterface $renderer,
        QuizTemplateService $service,
        SessionInterface $session,
        QuestionTemplateService $questionTemplateService,
        QuizTemplateFactory $quizTemplateFactory
    )
    {
        parent::__construct($renderer);
        $this->questionTemplateService = $questionTemplateService;
        $this->session = $session;
        $this->service = $service;
        $this->quizTemplateFactory = $quizTemplateFactory;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, array $attributes): Response
    {
        $questionsIds = $request->getParameter("questions");
        $quizTemplate = $this->quizTemplateFactory->createFromRequest(
            $request,
            $this->session,
            $attributes,
            "name",
            "description",
            "questions"
        );
        try {
            $this->service->add($quizTemplate, $questionsIds);
        }
        catch (QuizTemplateAlreadyExistsException $exception){
            $questions = $this->questionTemplateService->getAll(0, 0);
            return $this->renderer->renderView(
                self::PAGE_DETAILS,
                [
                    "errorMessage" => $exception->getMessage(),
                    "questions" => $questions
                ]
            );
        }

        return $this->createResponse($request, "301", "Location", ["/dashboard/quizzes"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function update(Request $request, array $attributes): Response
    {
        $this->session->start();
        $questionsIds = $request->getParameter("questions");
        $quizTemplate = $this->quizTemplateFactory->createFromRequest(
            $request,
            $this->session,
            $attributes,
            "name",
            "description",
            "questions"
        );
       $this->service->update($quizTemplate, $questionsIds);

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
        $numberOfQuizzes = $this->service->getCount();
        $properties = $request->getParameters();
        $currentPage = $properties["page"] ?? 1;
        $paginator =  new PaginatorService($numberOfQuizzes, $currentPage);
        $quizzes = $this->service->getAll($paginator->getResultsPerPage(), $currentPage);

        return $this->renderer->renderView(
            self::PAGE_LISTING,
            [
                "paginator" => $paginator,
                "quizzes" => $quizzes
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showNewQuizPage(Request $request, array $attributes): Response
    {
        $questions = $this->questionTemplateService->getAll(0, 0);


        return $this->renderer->renderView(
            "admin-quiz-details.phtml", [
            "questions" => $questions
        ]);
    }
    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * Returns the page for the edit functionality for the quizzes
     */
    public function showEditQuizPage(Request $request, array $attributes): Response
    {
        $id = $attributes['id'] ??  0;
        $quiz = $this->service->getQuizDetails($id);
        $thisQuizQuestions = $this->questionTemplateService->getAllQuestionIdsFromOneQuiz($id);
        $questions = $this->questionTemplateService->getAll(0, 0);

        return $this->renderer->renderView(
            "admin-quiz-details.phtml",
            [
                "quiz" => $quiz,
                "thisQuizQuestions" => $thisQuizQuestions,
                "questions" => $questions
            ]);
    }
}