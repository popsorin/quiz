<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quiz\Adapter\QuestionTemplateAdapter;
use Quiz\Adapter\QuizTemplateAdapter;
use Quiz\Service\QuestionInstanceService;
use Quiz\Service\QuestionTemplateService;

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
    private $service;

    /**
     * @var QuestionTemplateService
     */
    private $questionTemplateService;

    /**
     * QuestionInstanceController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     * @param QuestionInstanceService $service
     * @param QuestionTemplateService $questionTemplateService
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session,
        QuestionInstanceService $service,
        QuestionTemplateService $questionTemplateService
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
        $this->questionTemplateService = $questionTemplateService;
    }

    public function instance(Request $request, array $attributes)
    {
        $id = $attributes["id"];
        $questionTemplate = $this->questionTemplateService->getQuestions($id);
        $this->service->add($questionTemplate, $id);
        $questionNumber = 0;
        $this->session->start();
        $this->session->set("questionNumber",$questionNumber);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "question"=>$questionTemplate[0]
            ]
        );
    }

    public function save(Request $request, array $attributes)
    {
        $this->session->start();
        $questions = $this->session->get("questions");
        $answers = $this->session->get("answers");
        $answeredQuestions = $this->session->get("answeredQuestions");
        $questionLength = count($questions) -1;
        $text = $questions[$questionLength]->getText();
        $type = $questions[$questionLength]->getType();

        $this->service->add(
            ["text" => $text ,
                "quizInstanceId" => $attributes["id"],
                "type" => $type,
                "questionTemplateId" => $questionLength,
                "answer" => $request->getParameter("answer")
            ]
        );

        $answeredQuestions[] = $questions[$questionLength];
        $this->session->set("answeredQuestions", $answeredQuestions);
        $answers[] = $request->getParameter("answer");
        $this->session->set("answers", $answers);
        unset($questions[$questionLength]);
        shuffle($questions);
        if(count($questions) <= 0){
            return self::createResponse($request, "301", "Location", ["/homepage/success/" . $attributes["id"]]);
        }
        $this->session->set("questions", $questions);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "name" => $this->session->get("name"),
                "question" => $questions[$questionLength-1]
            ]
        );
    }
}