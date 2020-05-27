<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizInstanceService;
use Quiz\Service\QuizTemplateService;
use Quiz\Service\UserService;

class ResultsController extends AbstractController
{
    const RESULTS_PER_PAGE = 4;
    const RESULTS_LISTING_PAGE = "admin-results-listing.phtml";
    const RESULT_PAGE = "admin-results-listing.phtml";

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuizInstanceService
     */
    private $quizInstanceService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * ResultsController constructor.
     * @param SessionInterface $session
     * @param QuizInstanceService $quizInstanceService
     * @param RendererInterface $renderer
     * @param UserService $userService
     */
    public function __construct(
        SessionInterface $session,
        QuizInstanceService $quizInstanceService,
        RendererInterface $renderer,
        UserService $userService
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->quizInstanceService = $quizInstanceService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getResults(Request $request, array $attributes): Response
    {
        $quizInstances = $this->quizInstanceService->getALL();
        $users = $this->userService->getAllByQuizInstances($quizInstances);

        return $this->renderer->renderView(
            self::RESULTS_LISTING_PAGE,
            [
                "quizzes" => $quizInstances,
                "users" => $users
            ]
        );
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getCandidateResult(Request $request, array $attributes): Response
    {
        $quizInstances = $this->quizInstanceService->getALL();
        $users = $this->userService->getAllByQuizInstances($quizInstances);

        return $this->renderer->renderView(
            self::RESULT_PAGE,
            [
                "quizzes" => $quizInstances,
                "users" => $users
            ]
        );
    }
}