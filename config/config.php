<?php

use Framework\Dispatcher\Dispatcher;
use Framework\Router\Router;

return [

    Dispatcher::CONFIG_KEY_DISPATCHER => [Dispatcher::CONFIG_KEY_NAMESPACE => 'Quiz\Controller',
                                      Dispatcher::CONFIG_KEY_SUFFIX => 'Controller'],
    Router::CONFIG_KEY_ROUTER=> [
        'user_controller_add' =>
        [
            Router::CONFIG_KEY_PATH => '/user/(?<id>\d+)',
            Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
            Router::CONFIG_KEY_ACTION  => 'getAll',
            Router::CONFIG_KEY_METHOD => 'GET'
        ],
        'user_controller_get' =>
            [
                Router::CONFIG_KEY_PATH => '/user/(?<user>\d+)/role/admin',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION  => 'add',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'user_controller_set' =>
        [
            Router::CONFIG_KEY_PATH => '/user/(?<id>1|2)',
            Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
            Router::CONFIG_KEY_ACTION => 'delete',
            Router::CONFIG_KEY_METHOD => 'DELETE'
        ],
        'user_controller_login' =>
            [
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'login',
                Router::CONFIG_KEY_METHOD => 'POST'
            ]
    ]
];