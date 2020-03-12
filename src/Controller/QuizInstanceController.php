<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
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
     * QuizInstanceController constructor.
     * @param RendererInterface $renderer
     * @param QuizTemplateService $quizTemplateService
     * @param SessionInterface $session
     * @param QuestionTemplateService $questionTemplateService
     * @param QuizInstanceService $service
     */
    public function __construct(
        RendererInterface $renderer,
        QuizTemplateService $quizTemplateService,
        SessionInterface $session,
        QuestionTemplateService $questionTemplateService,
        QuizInstanceService $service
    ) {
        parent::__construct($renderer);
        $this->quizTemplateService = $quizTemplateService;
        $this->session = $session;
        $this->questionTemplateService = $questionTemplateService;
        $this->service = $service;
    }

    public function getQuiz(Request $request, array $attributes)
    {
        $this->session->start();
        $quizTemplate = $this->quizTemplateService->getOneQuiz(["id" =>$attributes["id"]]);

        $this->service->add($attributes["id"], $quizTemplate);

        $questionsId = $this->questionTemplateService->getAllLinked($quizTemplate->getId());

        $questions = $this->questionTemplateService->getAllFiltered($questionsId);

        $this->session->set("questions", $questions);

        $this->session->set("answeredQuestions", []);
        $this->session->set("answers", []);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "name" => $this->session->get("name"),
                "question" => $questions[count($questions) -1]
            ]
        );
    }
}