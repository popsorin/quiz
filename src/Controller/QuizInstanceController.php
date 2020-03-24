<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Service\QuestionInstanceService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizInstanceService;
use Quiz\Service\QuizTemplateService;

class QuizInstanceController extends AbstractController
{
    const LISTING_PAGE = "candidate-quiz-page.phtml";
    /**
     * @var QuizTemplateService
     */
    private $quizTemplateService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;

    /**
     * @var QuizInstanceService
     */
    private $service;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * QuizInstanceController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $quizTemplateService
     * @param SessionInterface $session
     * @param QuestionTemplateService $questionTemplateService
     * @param QuizInstanceService $service
     * @param QuestionInstanceService $questionInstanceService
     */
    public function __construct(
        RendererInterface $renderer,
        QuizTemplateService $quizTemplateService,
        SessionInterface $session,
        QuestionTemplateService $questionTemplateService,
        QuizInstanceService $service,
        QuestionInstanceService $questionInstanceService
    )
    {
        parent::__construct($renderer);
        $this->quizTemplateService = $quizTemplateService;
        $this->session = $session;
        $this->questionTemplateService = $questionTemplateService;
        $this->service = $service;
        $this->questionInstanceService = $questionInstanceService;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * Retrieves a quiz that was chosen by the candidate from the database
     * and redirects to the instance so the first question can be displayed
     */
    public function startQuiz(Request $request, array $attributes): Response
    {
        $this->session->start();

        $quizTemplateId = $attributes["quizTemplateId"];
        $quizTemplate = $this->quizTemplateService->getOneQuiz(["id" => $quizTemplateId]);
        $quizInstance = $this->service->makeQuizInstance($quizTemplate, $quizTemplateId, $this->session->get("id"));
        $this->service->add($quizInstance);

        $quizInstanceId = $quizInstance->getId();
        $questionTemplates = $this->questionTemplateService->getQuestions($quizTemplateId);
        foreach ($questionTemplates as $questionTemplate)
        {
            $questionInstance =$this->questionInstanceService->getQuestionInstance($questionTemplate, $quizInstanceId, $questionTemplate->getId());
            $this->questionInstanceService->add($questionInstance);
        }

        return $this->createResponse($request, 301, "Location", ["/homepage/quiz/$quizInstanceId/question/1"]);

    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function save(Request $request, array  $attributes): Response
    {
        return $this->createResponse($request, 301, "Location", ["/homepage/quiz/questions"]);
    }
}