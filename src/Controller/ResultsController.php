<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Quiz\Persistency\Repositories\AnswerTextInstanceRepository;
use Quiz\Persistency\Repositories\QuestionInstanceRepository;
use Quiz\Persistency\Repositories\QuizInstanceRepository;
use Quiz\Service\AnswerInstanceService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizInstanceService;
use Quiz\Service\QuizTemplateService;
use Quiz\Service\UserService;

class ResultsController extends AbstractController
{
    const RESULTS_PER_PAGE = 4;
    const RESULTS_LISTING_PAGE = "admin-results-listing.phtml";
    const RESULT_PAGE = "admin-results.phtml";

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var QuizInstanceRepository
     */
    private $quizInstanceRepository;

    /**
     * @var QuestionInstanceRepository
     */
    private $questionInstanceRepository;

    /**
     * @var AnswerInstanceService
     */
    private $answerInstanceService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * ResultsController constructor.
     * @param SessionInterface $session
     * @param QuizInstanceRepository $quizInstanceRepository
     * @param QuestionInstanceRepository $questionInstanceRepository
     * @param AnswerInstanceService $answerInstanceService
     * @param RendererInterface $renderer
     * @param UserService $userService
     */
    public function __construct(
        SessionInterface $session,
        QuizInstanceRepository $quizInstanceRepository,
        QuestionInstanceRepository $questionInstanceRepository,
        AnswerInstanceService $answerInstanceService,
        RendererInterface $renderer,
        UserService $userService
    )
    {
        parent::__construct($renderer);
        $this->session = $session;
        $this->quizInstanceRepository = $quizInstanceRepository;
        $this->userService = $userService;
        $this->questionInstanceRepository = $questionInstanceRepository;
        $this->answerInstanceService = $answerInstanceService;
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
        $quizInstance = $this->quizInstanceRepository->find($attributes["quizInstanceId"]);
        $user = $this->userService->findUserById($quizInstance->getUserId());
        $questionsInstances = $this->questionInstanceRepository->getQuestionsInstances($quizInstance->getId());
        $answersInstances = $this->answerInstanceService->getAll($questionsInstances);

        return $this->renderer->renderView(
            self::RESULT_PAGE,
            [
                "user" => $user,
                "questionsInstances" => $questionsInstances,
                "answersInstances" => $answersInstances
            ]
        );
    }
}