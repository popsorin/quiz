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

class QuestionInstanceController extends AbstractController
{
    const LISTING_PAGE = "candidate-quiz-page.phtml";

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;


    /**
     * QuestionInstanceController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     * @param QuestionInstanceService $service
     * @param QuestionTemplateService $questionTemplateService
     * @param QuizInstanceService $quizInstanceService
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session,
        QuestionInstanceService $service,
        QuestionTemplateService $questionTemplateService,
        QuizInstanceService $quizInstanceService
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->questionInstanceService = $service;
        $this->questionTemplateService = $questionTemplateService;
        $this->quizInstanceService = $quizInstanceService;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function displayQuestion(Request $request, array  $attributes): Response
    {
        $currentQuestionInstanceNumber = (int)$attributes["currentQuestionInstanceNumber"];
        $quizInstanceId = $attributes["quizInstanceId"];
        $nrQuestions = $this->quizInstanceService->getNumberOfQuestions($quizInstanceId);

        if($nrQuestions < $currentQuestionInstanceNumber) {
            return $this->createResponse($request, 301,"Location", ["/homepage/success/$quizInstanceId"]);
        }

        $questionInstance = $this->questionInstanceService->getOne($quizInstanceId, $currentQuestionInstanceNumber-1);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "question" => $questionInstance
            ]
        );
    }
}