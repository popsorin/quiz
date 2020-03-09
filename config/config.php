<?php

use Framework\Dispatcher\Dispatcher;
use Framework\Router\Router;

return [

    Dispatcher::CONFIG_KEY_DISPATCHER => [Dispatcher::CONFIG_KEY_NAMESPACE => 'Quiz\Controller',
        Dispatcher::CONFIG_KEY_SUFFIX => 'Controller'],
    Router::CONFIG_KEY_ROUTER => [
        'user_controller_add' =>
            [
                Router::CONFIG_KEY_PATH => '/user/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'getAll',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_get' =>
            [
                Router::CONFIG_KEY_PATH => '/user/(?<user>\d+)/role/admin',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'add',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'user_controller_getAll' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'getAll',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_getAllFiltered' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'getAll',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_set' =>
            [
                Router::CONFIG_KEY_PATH => '/user/(?<id>1|2)',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'delete',
                Router::CONFIG_KEY_METHOD => 'DELETE'
            ],
        'user_controller_displayLogin' =>
            [
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Login',
                Router::CONFIG_KEY_ACTION => 'displayLogin',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_login' =>
            [
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Login',
                Router::CONFIG_KEY_ACTION => 'login',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'user_controller_dashboard' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Admin',
                Router::CONFIG_KEY_ACTION => 'showDashBoard',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_logout' =>
            [
                Router::CONFIG_KEY_PATH => '/logout',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Login',
                Router::CONFIG_KEY_ACTION => 'logout',
                Router::CONFIG_KEY_METHOD => 'GET'
            ]
    ]
];