<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Service\AnswerInstanceService;
use Quiz\Service\QuestionInstanceService;
use Quiz\Service\QuizTemplateService;

class CandidateController extends AbstractController
{
    const QUESTIONS_PER_PAGE = 4;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuizTemplateService
     */
    private $service;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * @var AnswerInstanceService
     */
    private $answerInstanceServoce;

    /**
     * CandidateController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     * @param QuizTemplateService $service
     * @param QuestionInstanceService $questionInstanceService
     * @param AnswerInstanceService $answerInstanceService
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session,
        QuizTemplateService $service,
        QuestionInstanceService $questionInstanceService,
        AnswerInstanceService $answerInstanceService
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
        $this->questionInstanceService = $questionInstanceService;
        $this->answerInstanceServoce = $answerInstanceService;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showHomepage(Request $request, array $attributes): Response
    {
        $this->session->start();
        if (($this->session->get("name")) === null) {

            return self::createResponse($request, "301", "Location", ["/"]);
        }

        $page = ($request->getParameter("page")) ?? 1;
        $props = $this->service->getAll($page, self::QUESTIONS_PER_PAGE);

        return $this->renderer->renderView(
            "candidate-quiz-listing.phtml",
            [
                "name" => ($this->session->get("name")),
                "quizzes" => $props['quizzes'],
                "page" => $props['page'],
                "entitiesNumber" => $props['entitiesNumber'],
                "limit" => $props['limit']
            ]);

    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * Display the results page to the contestant
     */
    public function success(Request $request, array $attributes): Response
    {
        $this->session->start();
        $quizInstanceId = $attributes["quizInstanceId"];
        $questions = $this->questionInstanceService->getAllByQuizInstanceId($quizInstanceId,0,0);
        $answers = $this->answerInstanceServoce->getAll($questions);
      
        return $this->renderer->renderView(
            "quiz-success-page.phtml",
            [
            "questions" => $questions,
            "answers" => $answers
            ]
        );
    }
}