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
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use Quiz\Service\Paginator;
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
     * @var QuestionTemplateRepository
     */
    private $questionTemplateRepository;

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
     * @param QuestionTemplateRepository $questionTemplateRepository
     * @param SessionInterface $session
     * @param QuizTemplateService $quizTemplateService
     * @param QuestionTemplateFactory $questionTemplateFactory
     * @param URLHelper $urlHelper
     */
    public function __construct(
        RendererInterface $renderer,
        QuestionTemplateRepository $questionTemplateRepository,
        SessionInterface $session,
        QuizTemplateService $quizTemplateService,
        QuestionTemplateFactory $questionTemplateFactory,
        URLHelper $urlHelper
    ) {
        parent::__construct($renderer);
        $this->quizTemplateService = $quizTemplateService;
        $this->session = $session;
        $this->questionTemplateRepository = $questionTemplateRepository;
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
        $this->questionTemplateRepository->insertOnDuplicateKeyUpdate($questionTemplate);

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
        $this->questionTemplateRepository->insertOnDuplicateKeyUpdate($questionTemplate);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->questionTemplateRepository->deleteById($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $requestParameterBag = new RequestParameterBag($request->getParameters());
        $currentPage = $request->getParameter("page") ?? 1;
        $filters = $requestParameterBag->getFilterParameters();
        $paginator = new Paginator($this->questionTemplateRepository->getCount($filters), $currentPage);
        $urlQuery = $this->urlHelper->buildURLQuery($requestParameterBag);

        $questionTemplates = $this->questionTemplateRepository->findBy(
            array_merge($filters, $requestParameterBag->getSearchParameters()),
            $requestParameterBag->getSortParameters(),
            $paginator->getResultsPerPage() * ($currentPage - 1),
            $paginator->getResultsPerPage()
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
        $question = $this->questionTemplateRepository->find($attributes["id"]);

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