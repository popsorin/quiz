<?php

namespace Quiz\Controller;

use Exception;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Factory\QuestionTemplateFactory;
use Quiz\Service\PaginatorService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizTemplateService;
use ReflectionException;

/**
 * Class QuestionTemplateController
 * @package Quiz\Controller
 */
class QuestionTemplateController extends AbstractController
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';

    /**
     * @var QuizTemplateService
     */
    private $boundedService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuestionTemplateService
     */
    private $service;

    /**
     * @var QuestionTemplateFactory
     */
    private $factory;

    /**
     * QuestionTemplateController constructor.
     * @param RendererInterface $renderer
     * @param QuestionTemplateService $service
     * @param SessionInterface $session
     * @param QuizTemplateService $boundedService
     * @param QuestionTemplateFactory $factory
     */
    public function __construct(
        RendererInterface $renderer,
        QuestionTemplateService $service,
        SessionInterface $session,
        QuizTemplateService $boundedService,
        QuestionTemplateFactory $factory
    ) {
        parent::__construct($renderer);
        $this->boundedService = $boundedService;
        $this->session = $session;
        $this->service = $service;
        $this->factory = $factory;
    }


    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     * @throws Exception
     */
    public function add(Request $request, array $attributes): Response
    {
        $questionTemplate = $this->factory->createFromRequest($request);
        $this->service->insertOnDuplicateKeyUpdate($questionTemplate);

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
        $questionTemplate = $this->factory->createFromRequest($request);
        $questionTemplate->setId($attributes["id"]);
        $this->service->insertOnDuplicateKeyUpdate($questionTemplate);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function delete(Request $request, array $attributes): Response
    {
        $this->service->deleteById($attributes["id"]);

        return $this->createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes): Response
    {
        $parameters = $request->getParameters();
        $page = $parameters["page"] ?? 1;
        $numberOfQuestions = $this->service->getCount();
        $paginator = new PaginatorService($numberOfQuestions, $page);
        $questionTemplates = $this->service->getAll($paginator->getResultsPerPage(), $page);

        return $this->renderer->renderView(
            self::LISTING_PAGE,
            [
                "questions" => $questionTemplates,
                "paginator" => $paginator,
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getQuestionDetailsUpdate(Request $request, array $attributes): Response
    {
        $question = $this->service->getQuestionDetails($attributes["id"]);

        return $this->renderer->renderView(
            "admin-question-details.phtml",
            [
                "question" => $question->getText()
            ]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getQuestionDetailsAdd(Request $request, array $attributes): Response
    {
        return $this->renderer->renderView("admin-question-details.phtml", []);
    }
}