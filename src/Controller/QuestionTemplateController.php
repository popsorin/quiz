<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\User;
use Quiz\Service\AbstractService;
use ReallyOrm\Entity\EntityInterface;

/**
 * Class QuestionTemplateController
 * @package Quiz\Controller
 */
class QuestionTemplateController extends Controller
{
    const LISTING_PAGE = 'admin-questions-listing.phtml';
    const QUESTIONS_PER_PAGE = 4;

    /**
     * @var AbstractService
     */
    private $boundedService;

    /**
     * QuestionTemplateController constructor.
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
     * @param array $attributes
     * @return Response
     */
    public function add(Request $request, array $attributes)
    {
        $id = isset($attributes['id']) ? $attributes['id'] : null;
        $this->service->add($id, $request->getParameters());

        return self::createResponse($request, "301", "Location", ["/dashboard/questions"]);
    }


    public function delete(Request $request, array $attributes)
    {
        $this->service->deleteById($attributes["id"]);

        return self::createResponse($request, "301", "Location", ["/dashboard/questions?page="]);
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