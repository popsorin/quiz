<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quiz\Adapter\QuizTemplateAdapter;
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
        $id = $attributes["id"];
        $quizTemplate = $this->quizTemplateService->getOneQuiz(["id" => $id]);
        $adaptor = new QuizTemplateAdapter();
        $quizInstance = $adaptor->getQuizInstance($quizTemplate, $id);
        $this->service->add($quizInstance);
        $this->session->set("quizInstanceId", $quizInstance);

        return self::createResponse($request, 301, "Location", ["/homepage/quiz/$id/instance/questions/"]);

    }

    public function save(Request $request, array  $attributes)
    {

    }
}