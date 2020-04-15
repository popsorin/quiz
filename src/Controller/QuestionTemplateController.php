<?php

namespace Quiz\Controller;

use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\QuestionTemplate;
use Quiz\Factory\QuestionTemplateFactory;
use Quiz\Service\Paginator;
use Quiz\Service\ParameterBag;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizTemplateService;
use Quiz\Service\RequestParameterBag;
use Quiz\Service\URLHelper;
use ReflectionException;

/**
 * Class QuestionTemplateController
 * @package Quiz\Controller
 */
class QuestionTemplateController extends AbstractController
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';
    const QUESTION_TYPES = ["text", "code"];

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
     * @var QuestionTemplateFactory
     */
    private $questionTemplateFactory;

    /**
     * @var URLHelper
     */
    private $urlHelper;

    /**
     * QuestionTemplateController constructor.
     * @param RendererInterface $renderer
     * @param QuestionTemplateService $questionTemplateService
     * @param SessionInterface $session
     * @param QuizTemplateService $quizTemplateService
     * @param QuestionTemplateFactory $questionTemplateFactory
     * @param URLHelper $urlHelper
     */
    public function __construct(
        RendererInterface $renderer,
        QuestionTemplateService $questionTemplateService,
        SessionInterface $session,
        QuizTemplateService $quizTemplateService,
        QuestionTemplateFactory $questionTemplateFactory,
        URLHelper $urlHelper
    ) {
        parent::__construct($renderer);
        $this->quizTemplateService = $quizTemplateService;
        $this->session = $session;
        $this->questionTemplateService = $questionTemplateService;
        $this->questionTemplateFactory = $questionTemplateFactory;
        $this->urlHelper = $urlHelper;
    }


    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, array $attributes): Response
    {
        $questionTemplate = $this->questionTemplateFactory->createFromRequest($request);
        $this->questionTemplateService->add($questionTemplate);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws ReflectionException
     */
    public function update(Request $request, array $attributes): Response
    {
        $questionTemplate = $this->questionTemplateFactory->createFromRequest($request);
        $questionTemplate->setId($attributes["id"]);
        $this->questionTemplateService->update($questionTemplate);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->questionTemplateService->deleteById($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $parameterBag = new RequestParameterBag($request->getParameters());
        $currentPage = $request->getParameter("page") ?? 1;
        $numberOfUsers = $this->questionTemplateService->countQuestions($parameterBag->getParameters());
        $paginator = new Paginator($numberOfUsers, $currentPage);
        $urlQuery = $this->urlHelper->buildURLQuery($parameterBag);

        $questionTemplates = $this->questionTemplateService->getAll(
            $parameterBag->getParameters(),
            $paginator->getResultsPerPage(),
            $currentPage
        );

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "questions" => $questionTemplates,
                "paginator" => $paginator,
                "types" => self::QUESTION_TYPES,
                "urlQuery" => $urlQuery,
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showEditQuestionPage(Request $request, array $attributes): Response
    {
        $question = $this->questionTemplateService->getQuestionDetails($attributes["id"]);

        return $this->renderer->renderView(
            "admin-question-details.phtml",
            [
                "question" => $question
            ]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function showNewQuestionPage(Request $request, array $attributes): Response
    {
        $question = new QuestionTemplate("", "", "");
        return $this->renderer->renderView(
            "admin-question-details.phtml",
            [
                "question" => $question
            ]);
    }
}