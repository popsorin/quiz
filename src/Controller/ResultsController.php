<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Persistency\Repositories\QuizInstanceRepository;
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
     * @var QuizInstanceRepository
     */
    private $quizInstanceRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * ResultsController constructor.
     * @param SessionInterface $session
     * @param QuizInstanceRepository $quizInstanceRepository
     * @param RendererInterface $renderer
     * @param UserService $userService
     */
    public function __construct(
        SessionInterface $session,
        QuizInstanceRepository $quizInstanceRepository,
        RendererInterface $renderer,
        UserService $userService
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->quizInstanceRepository = $quizInstanceRepository;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function getResults(Request $request, array $attributes): Response
    {
        $quizInstances = $this->quizInstanceRepository->findBy([], [],0,0);
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
        $quizInstances = $this->quizInstanceRepository->getALL();
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