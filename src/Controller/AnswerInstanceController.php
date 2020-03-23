<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Factory\AnswerChoiceInstanceFactory;
use Quiz\Factory\AnswerTextInstanceFactory;
use Quiz\Service\AnswerInstanceService;
use Quiz\Service\QuestionInstanceService;

class AnswerInstanceController extends AbstractController
{
    const LISTING_PAGE = "candidate-quiz-page.phtml";

    /**
     * @var AnswerInstanceService
     */
    private $answerInstanceService;

    /**
     * @var QuestionInstanceService
     */
    private $questionInstanceService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var AnswerChoiceInstanceFactory
     */
    private $answerChoiceInstanceFactory;

    /**
     * @var AnswerTextInstanceFactory
     */
    private $answerTextInstanceFactory;

    /**
     * AnswerInstanceController constructor.
     * @param RendererInterface $renderer
     * @param AnswerInstanceService $answerInstanceService
     * @param QuestionInstanceService $questionInstanceService
     * @param SessionInterface $session
     * @param AnswerChoiceInstanceFactory $answerChoiceInstanceFactory
     * @param AnswerTextInstanceFactory $answerTextInstanceFactory
     */
    public function __construct(
        RendererInterface $renderer,
        AnswerInstanceService $answerInstanceService,
        QuestionInstanceService $questionInstanceService,
        SessionInterface $session,
        AnswerChoiceInstanceFactory $answerChoiceInstanceFactory,
        AnswerTextInstanceFactory $answerTextInstanceFactory
    ) {
        parent::__construct($renderer);
        $this->session = $session;
        $this->answerInstanceService = $answerInstanceService;
        $this->answerChoiceInstanceFactory = $answerChoiceInstanceFactory;
        $this->answerTextInstanceFactory = $answerTextInstanceFactory;
        $this->questionInstanceService = $questionInstanceService;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function add(Request $request, array $attributes): Response
    {
        $this->session->start();
        $offset= $this->session->get("offset");
        $limit = $this->session->get("limit");
        $quizInstanceId = $this->session->get("quizInstanceId");

        $questionInstance = $this->questionInstanceService->getOne($quizInstanceId, $offset++, $limit++);
        $type = $questionInstance->getType();
        $answer = ($type === "text") ?
            $this->answerTextInstanceFactory->createFromRequest($request, "questionInstanceId", "answer") :
            $this->answerChoiceInstanceFactory->createFromRequest($request,"answer", "questionInstance", "isSelected", "isCorrect");
        $answer->setQuestionInstanceId($questionInstance->getId());
        $this->answerInstanceService->add($answer, $questionInstance);

        $this->session->set("offset", $offset);
        $this->session->set("limit", $limit);

        return self::createResponse($request, 301, "Location", ["/homepage/quiz/questions"]);
    }
}