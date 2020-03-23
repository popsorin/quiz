<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
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

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * Saves the questionInstances found in the quizInstance and displays the first one
     */
    public function instanceQuestions(Request $request, array $attributes): Response
    {
        $this->session->start();
        $quizTemplateId = $this->session->get("quizTemplateId");
        $quizInstanceId = $this->session->get("quizInstanceId");
        $questionsTemplate = $this->questionTemplateService->getQuestions($quizTemplateId);
        $this->session->set("nrQuestionsInstance", count($questionsTemplate));

        foreach ($questionsTemplate as $questionTemplate)
        {
            $questionInstance =$this->service->getQuestionInstance($questionTemplate, $quizInstanceId, $questionTemplate->getId());
            $this->service->add($questionInstance);
        }

        return self::createResponse($request, 301, "Location", ["/homepage/quiz/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function displayQuestion(Request $request, array  $attributes): Response
    {
        $this->session->start();
        $offset= $this->session->get("offset");
        $limit = $this->session->get("limit");
        $nrQuestions = $this->session->get("nrQuestionsInstance");

        if($nrQuestions < $limit) {
            return self::createResponse($request, 301,"Location", ["/homepage/success"]);
        }

        $quizInstanceId = $this->session->get("quizInstanceId");
        $questionInstance = $this->service->getOne($quizInstanceId, $offset, $limit);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "question"=>$questionInstance
            ]
        );
    }
}