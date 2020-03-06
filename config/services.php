<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Quiz\Controller\UserController;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Quiz\Entities\User;
use Quiz\Persistancy\Repositories\UserRepository;
use ReallyOrm\Test\Hydrator\Hydrator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


$databaseConfig = require 'db_config.php';

$dsn = "mysql:host={$databaseConfig['host']};dbname={$databaseConfig['db']};charset={$databaseConfig['charset']}";
$container = new ContainerBuilder();
$config = require "../config/config.php";

$container->setParameter('routerConfig', $config[Router::CONFIG_KEY_ROUTER]);
$container->register(RouterInterface::class, Router::class)
            ->addArgument('%routerConfig%');

$baseViewPath = __DIR__ . '/../src/views';
$container->setParameter("baseViewPath", $baseViewPath);
$container->register(RendererInterface::class, Renderer::class)
          ->addArgument('%baseViewPath%');

$container->register(Hydrator::class, Hydrator::class);

$container->setParameter("PDO", new PDO($dsn, $databaseConfig['user'], $databaseConfig['pass'], $databaseConfig["PDOOptions"]));
$container->register(UserRepository::class, UserRepository::class)
          ->addArgument("%PDO%")
          ->addArgument(User::class)
          ->addArgument(new Reference(Hydrator::class))
          ->addArgument("user");

$container->register(UserController::class, UserController::class)
            ->addArgument(new Reference(RendererInterface::class))
            ->addArgument($container->findDefinition(UserRepository::class))
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