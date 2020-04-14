<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Session;
use Quiz\Controller\AdminController;
use Quiz\Controller\AnswerInstanceController;
use Quiz\Controller\CandidateController;
use Quiz\Controller\LoginController;
use Quiz\Controller\QuestionInstanceController;
use Quiz\Controller\QuestionTemplateController;
use Quiz\Controller\QuizInstanceController;
use Quiz\Controller\QuizTemplateController;
use Quiz\Controller\ResultsController;
use Quiz\Controller\UserController;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Quiz\Entity\AnswerChoiceInstance;
use Quiz\Entity\AnswerChoiceTemplate;
use Quiz\Entity\AnswerTextInstance;
use Quiz\Entity\AnswerTextTemplate;
use Quiz\Entity\QuestionInstance;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\QuizInstance;
use Quiz\Entity\QuizTemplate;
use Quiz\Entity\User;
use Quiz\Factory\AnswerChoiceInstanceFactory;
use Quiz\Factory\AnswerTextInstanceFactory;
use Quiz\Factory\QuestionTemplateFactory;
use Quiz\Factory\QuizTemplateFactory;
use Quiz\Factory\UserFactory;
use Quiz\Persistency\Repositories\AnswerChoiceInstanceRepository;
use Quiz\Persistency\Repositories\AnswerChoiceTemplateRepository;
use Quiz\Persistency\Repositories\AnswerTextInstanceRepository;
use Quiz\Persistency\Repositories\AnswerTextTemplateRepository;
use Quiz\Persistency\Repositories\QuestionInstanceRepository;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use Quiz\Persistency\Repositories\QuizInstanceRepository;
use Quiz\Persistency\Repositories\QuizTemplateRepository;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Service\AnswerInstanceService;
use Quiz\Service\LoginService;
use Quiz\Service\QuestionInstanceService;
use Quiz\Service\QuestionTemplateService;
use Quiz\Service\QuizInstanceService;
use Quiz\Service\QuizTemplateService;
use Quiz\Service\URLHelper;
use Quiz\Service\UserService;
use Quiz\Service\Validator\UserValidator;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\RepositoryManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;


$databaseConfig = require 'db_config.php';

$dsn = "mysql:host={$databaseConfig['host']};dbname={$databaseConfig['db']};charset={$databaseConfig['charset']}";

$container = new ContainerBuilder();
$config = require "../config/config.php";

$container->setParameter('routerConfig', $config[Router::CONFIG_KEY_ROUTER]);
$container->register(RouterInterface::class, Router::class)
    ->addArgument('%routerConfig%');

$baseViewPath = __DIR__ . '/../public/views/assets/';
$container->setParameter("baseViewPath", $baseViewPath);
$container->register(RendererInterface::class, Renderer::class)
    ->addArgument('%baseViewPath%');

$container->register(RepositoryManagerInterface::class, RepositoryManager::class);

$container->register(SessionInterface::class, Session::class);

$container->register(HydratorInterface::class, Hydrator::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));

$container->setParameter("PDO", new PDO($dsn, $databaseConfig['user'], $databaseConfig['pass'], $databaseConfig["PDOOptions"]));
$container->register(UserRepository::class, UserRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(User::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("user")
    ->addTag("repository");

$container->register(QuestionTemplateRepository::class, QuestionTemplateRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(QuestionTemplate::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("question_template")
    ->addTag("repository");

$container->register(QuizTemplateRepository::class, QuizTemplateRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(QuizTemplate::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("quiz_template")
    ->addTag("repository");

$container->register(QuizInstanceRepository::class, QuizInstanceRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(QuizInstance::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("quiz_instance")
    ->addTag("repository");

$container->register(QuestionInstanceRepository::class, QuestionInstanceRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(QuestionInstance::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("question_instance")
    ->addTag("repository");

$container->register(AnswerTextTemplateRepository::class, AnswerTextTemplateRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(AnswerTextTemplate::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("answer_text_template")
    ->addTag("repository");

$container->register(AnswerChoiceTemplateRepository::class, AnswerChoiceTemplateRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(AnswerChoiceTemplate::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("answer_choice_template")
    ->addTag("repository");

$container->register(AnswerTextInstanceRepository::class, AnswerTextInstanceRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(AnswerTextInstance::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("answer_text_instance")
    ->addTag("repository");

$container->register(AnswerChoiceInstanceRepository::class, AnswerChoiceInstanceRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(AnswerChoiceInstance::class)
    ->addArgument($container->findDefinition(HydratorInterface::class))
    ->addArgument("answer_choice_instance")
    ->addTag("repository");

foreach ($container->findTaggedServiceIds("repository") as $id => $attributes) {
    $repository = $container->findDefinition($id);
    $container->findDefinition(RepositoryManagerInterface::class)
        ->addMethodCall("addRepository", [$repository]);
}

$container->findDefinition(HydratorInterface::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));

$container->register(UserFactory::class, UserFactory::class);
$container->register(AnswerChoiceInstanceFactory::class, AnswerChoiceInstanceFactory::class);
$container->register(AnswerTextInstanceFactory::class, AnswerTextInstanceFactory::class);
$container->register(QuizTemplateFactory::class, QuizTemplateFactory::class);

$container->register(UserValidator::class, UserValidator::class)
    ->addArgument($container->findDefinition(UserRepository::class));
$container->register(QuestionTemplateFactory::class, QuestionTemplateFactory::class);

$container->register(URLHelper::class, URLHelper::class);

$container->register(SessionInterface::class, Session::class);

$container->register(LoginService::class, LoginService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(UserService::class, UserService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(QuestionTemplateService::class, QuestionTemplateService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(QuizTemplateService::class, QuizTemplateService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(QuizInstanceService::class, QuizInstanceService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(QuestionInstanceService::class, QuestionInstanceService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(AnswerInstanceService::class, AnswerInstanceService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));

$container->register(UserController::class, UserController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(UserRepository::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(UserFactory::class))
    ->addArgument($container->findDefinition(UserValidator::class))
    ->addArgument($container->findDefinition(URLHelper::class))
    ->addTag("controller");

$container->register(AdminController::class, AdminController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addTag("controller");

$container->register(LoginController::class, LoginController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(LoginService::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(UserFactory::class))
    ->addTag("controller");

$container->register(QuestionTemplateController::class, QuestionTemplateController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(QuestionTemplateService::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(QuizTemplateService::class))
    ->addArgument($container->findDefinition(QuestionTemplateFactory::class))
    ->addArgument($container->findDefinition(URLHelper::class))
    ->addTag("controller");

$container->register(QuizTemplateController::class, QuizTemplateController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(QuizTemplateService::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(QuestionTemplateService::class))
    ->addArgument($container->findDefinition(QuizTemplateFactory::class))
    ->addTag("controller");

$container->register(CandidateController::class, CandidateController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(QuizTemplateService::class))
    ->addArgument($container->findDefinition(QuestionInstanceService::class))
    ->addArgument($container->findDefinition(AnswerInstanceService::class))
    ->addTag("controller");

$container->register(QuizInstanceController::class, QuizInstanceController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(QuizTemplateService::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(QuestionTemplateService::class))
    ->addArgument($container->findDefinition(QuizInstanceService::class))
    ->addArgument($container->findDefinition(QuestionInstanceService::class))
    ->addTag("controller");

$container->register(QuestionInstanceController::class, QuestionInstanceController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(QuestionInstanceService::class))
    ->addArgument($container->findDefinition(QuestionTemplateService::class))
    ->addArgument($container->findDefinition(QuizInstanceService::class))
    ->addTag("controller");

$container->register(AnswerInstanceController::class, AnswerInstanceController::class)
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(AnswerInstanceService::class))
    ->addArgument($container->findDefinition(QuestionInstanceService::class))
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(AnswerChoiceInstanceFactory::class))
    ->addArgument($container->findDefinition(AnswerTextInstanceFactory::class))
    ->addTag("controller");

$container->register(ResultsController::class, ResultsController::class)
    ->addArgument($container->findDefinition(SessionInterface::class))
    ->addArgument($container->findDefinition(QuizInstanceService::class))
    ->addArgument($container->findDefinition(RendererInterface::class))
    ->addArgument($container->findDefinition(UserService::class))
    ->addTag("controller");

$container->setParameter('dispatcherConfig', $config[Dispatcher::CONFIG_KEY_DISPATCHER]);
$container->register(DispatcherInterface::class, Dispatcher::class)
    ->addArgument('%dispatcherConfig%');

$dispatcher = $container->findDefinition(DispatcherInterface::class);
foreach ($container->findTaggedServiceIds("controller") as $id => $attributes) {
    $controller = $container->findDefinition($id);
    $dispatcher->addMethodCall("addController", [$controller]);
}


return new SymfonyContainer($container);