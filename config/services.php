<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Session;
use Quiz\Controller\AdminController;
use Quiz\Controller\LoginController;
use Quiz\Controller\QuestionsTemplateController;
use Quiz\Controller\UserController;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Quiz\Entity\QuestionTemplate;
use Quiz\Entity\QuizTemplate;
use Quiz\Entity\User;
use Quiz\Persistency\Repositories\QuestionTemplateRepository;
use Quiz\Persistency\Repositories\QuizTemplateRepository;
use Quiz\Persistency\Repositories\UserRepository;
use Quiz\Services\AdminService;
use Quiz\Services\LoginService;
use Quiz\Services\QuestionTemplateService;
use Quiz\Services\QuizTemplateService;
use Quiz\Services\UserService;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\RepositoryManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


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

$container->register(HydratorInterface::class, Hydrator::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->setParameter("PDO", new PDO($dsn, $databaseConfig['user'], $databaseConfig['pass'], $databaseConfig["PDOOptions"]));
$container->register(UserRepository::class, UserRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(User::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addArgument("user")
    ->addTag("repository");

$container->register(QuestionTemplateRepository::class, QuestionTemplateRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(QuestionTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addArgument("question_template")
    ->addTag("repository");

$container->register(QuizTemplateRepository::class, QuizTemplateRepository::class)
    ->addArgument("%PDO%")
    ->addArgument(QuizTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addArgument("quiz_template")
    ->addTag("repository");

foreach ($container->findTaggedServiceIds("repository") as $id => $attributes) {
    $repository = $container->findDefinition($id);
    $container->findDefinition(RepositoryManagerInterface::class)
        ->addMethodCall("addRepository", [$repository]);
}

$container->findDefinition(HydratorInterface::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(SessionInterface::class, Session::class);

$container->register(LoginService::class, LoginService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(UserService::class, UserService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(QuestionTemplateService::class, QuestionTemplateService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(AdminService::class, AdminService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));
$container->register(QuizTemplateService::class, QuizTemplateService::class)
    ->addArgument($container->findDefinition(RepositoryManagerInterface::class));

$container->register(UserController::class, UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument($container->findDefinition(UserService::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addTag("controller");

$container->register(AdminController::class, AdminController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument($container->findDefinition(AdminService::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addTag("controller");

$container->register(LoginController::class, LoginController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument($container->findDefinition(LoginService::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addTag("controller");

$container->register(QuestionsTemplateController::class, QuestionsTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument($container->findDefinition(QuestionTemplateService::class))
    ->addArgument(new Reference(SessionInterface::class))
    ->addArgument($container->findDefinition(QuizTemplateService::class))
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