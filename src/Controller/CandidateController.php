<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Service\AnswerInstanceService;
use Quiz\Service\Paginator;
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
    private $quizTemplateService;

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
     * @param QuizTemplateService $quizTemplateService
     * @param QuestionInstanceService $questionInstanceService
     * @param AnswerInstanceService $answerInstanceService
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session,
        QuizTemplateService $quizTemplateService,
        QuestionInstanceService $questionInstanceService,
        AnswerInstanceService $answerInstanceService
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->quizTemplateService = $quizTemplateService;
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
        $user = $this->session->get("user");
        if ($user->getEmail() === null) {

            return self::createResponse($request, "301", "Location", ["/"]);
        }
        $numberOfQuizzes = $this->quizTemplateService->getCount([]);
        $currentPage = ($request->getParameter("page")) ?? 1;
        $paginator = new Paginator($numberOfQuizzes, $currentPage);
        $quizzes = $this->quizTemplateService->getAll($paginator->getResultsPerPage(), $currentPage);

        return $this->renderer->renderView(
            "candidate-quiz-listing.phtml",
            [
                "quizzes" => $quizzes,
                "paginator" => $paginator
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