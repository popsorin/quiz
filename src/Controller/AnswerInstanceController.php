<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\AnswerChoiceInstance;
use Quiz\Entity\AnswerTextInstance;
use Quiz\Factory\AnswerChoiceInstanceFactory;
use Quiz\Factory\AnswerTextInstanceFactory;
use Quiz\Service\AnswerInstanceService;
use Quiz\Service\QuestionInstanceService;
use ReallyOrm\Entity\EntityInterface;
use ReflectionException;

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
    )
    {
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
     * @throws ReflectionException
     */
    public function add(Request $request, array $attributes): Response
    {
        $offset = (int)$attributes["currentQuestionInstanceNumber"];
        $quizInstanceId = (int)$attributes["quizInstanceId"];

        $questionInstance = $this->questionInstanceService->getOneQuestion($quizInstanceId, $offset-1);
        $answer = $this->makeAnswer($request, $questionInstance->getType());
        $answer->setQuestionInstanceId($questionInstance->getId());
        $this->answerInstanceService->add($answer, $questionInstance->getType());
        $offset++;

        return $this->createResponse($request, 301, "Location", ["/homepage/quiz/$quizInstanceId/question/$offset"]);
    }

    /**
     * @param Request $request
     * @param string $answerType
     * @return AnswerChoiceInstance|AnswerTextInstance
     * @throws ReflectionException
     */
    private function makeAnswer(Request $request, string $answerType): EntityInterface
    {
        if($answerType === "text") {
           return $this->answerTextInstanceFactory->createFromRequest($request) ;
        }

        return $this->answerChoiceInstanceFactory->createFromRequest($request);
    }
}