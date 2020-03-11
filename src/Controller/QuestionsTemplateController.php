<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\User;
use Quiz\Services\AbstractService;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class QuestionsTemplateController
 * @package Quiz\Controller
 */
class QuestionsTemplateController extends Controller
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';
    const QUESTIONS_PER_PAGE = 4;

    /**
     * @var AbstractService
     */
    private $boundedService;

    /**
     * QuestionsTemplateController constructor.
     * @param RendererInterface $renderer
     * @param AbstractService $service
     * @param SessionInterface $session
     * @param AbstractService $boundedService
     */
    public function __construct(
        RendererInterface $renderer,
        AbstractService $service,
        SessionInterface $session,
        //change the name
        AbstractService $boundedService
    ) {
        parent::__construct($renderer, $service, $session);
        $this->boundedService = $boundedService;
    }

    /**
     * @param Request $request
     * @param string $className
     * @return EntityInterface|null
     */
    public function extractQuestion(Request $request, string $className): ?EntityInterface
    {
        return new $className($request->getParameter("answer"), $request->getParameter("question"), $request->getParameter("type"));
    }
    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function add(Request $request, array $attributes)
    {
        $question = self::extractQuestion($request, QuestionTemplate::class);
        $this->service->add($question, $attributes);

        return self::createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getAll(Request $request, array $attributes)
    {
        $props = $this->service->getAll($request, $attributes, self::QUESTIONS_PER_PAGE );
        return $this->renderer->renderView(
            $props['listingPage'],
            [
                "questions" => $props['questions'],
                "page" => $props['page'],
                "entitiesNumber" => $props['entitiesNumber'],
                "limit" => $props['limit']
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function questionDetails(Request $request, array $attributes)
    {
        $question = $this->service->questionDetails($request, $attributes);
        $quizzes = $this->boundedService->getAll($request, $attributes, 0);

        return $this->renderer->renderView("admin-question-details.phtml",
            [
                "question" => $question,
                "quizzes" => $quizzes['quizzes'],
            ]);
    }
}