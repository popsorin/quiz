<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quiz\Service\QuestionInstanceService;

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
     * QuestionInstanceController constructor.
     * @param RendererInterface $renderer
     * @param SessionInterface $session
     * @param QuestionInstanceService $service
     */
    public function __construct(
        RendererInterface $renderer,
        SessionInterface $session,
        QuestionInstanceService $service
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->service = $service;
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