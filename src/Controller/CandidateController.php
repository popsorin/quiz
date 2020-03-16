<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quiz\Entity\QuestionInstance;
use Quiz\Persistency\Repositories\QuizTemplateRepository;
use Quiz\Service\QuestionInstanceService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizTemplateService;

class CandidateController extends AbstractController
{
    const QUESTIONS_PER_PAGE = 4;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuestionTemplateService
     */
    private $service;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * CandidateController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     * @param QuizTemplateService $service
     * @param QuestionInstanceService $questionInstanceService
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session,
        QuizTemplateService $service,
        QuestionInstanceService $questionInstanceService
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
        $this->questionInstanceService = $questionInstanceService;
    }

    public function showHomepage(Request $request, array $attributes)
    {
        $this->session->start();
        if (($this->session->get("name")) === null) {

            return self::createResponse($request, "301", "Location", ["/"]);
        }

        $page = $request->getParameter("page") == null ? 1 : $request->getParameter("page");
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

    //success
    public function success(Request $request, array $attributes)
    {
        $this->session->start();
        $questions = $this->session->get("answeredQuestions");
        $answers = $this->session->get("answers");
        //need to implement a builder,for testing i will resume at making a new object
        $this->service->add();
        return $this->renderer->renderView(
            "quiz-success-page.phtml",
            [
            "questions" => $questions,
            "answers" => $answers
            ]
        );
    }
}