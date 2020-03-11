<?php

use Framework\Dispatcher\Dispatcher;
use Framework\Router\Router;

return [

    Dispatcher::CONFIG_KEY_DISPATCHER => [Dispatcher::CONFIG_KEY_NAMESPACE => 'Quiz\Controller',
        Dispatcher::CONFIG_KEY_SUFFIX => 'Controller'],
    Router::CONFIG_KEY_ROUTER => [
        'user_controller_delete' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users/delete/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'delete',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_userDetailsUpdate' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users/update/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'userDetails',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_userDetailsAdd' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users/add',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'userDetails',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'user_controller_add' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users/add',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'add',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'user_controller_update' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users/update/(?<id>\d+)',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'add',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'user_controller_users_getAll' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/users',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'User',
                Router::CONFIG_KEY_ACTION => 'getAll',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'question_controller_questionsTemplateDetailsAdd' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/questions-template/add',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'QuestionsTemplate',
                Router::CONFIG_KEY_ACTION => 'questionDetails',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'question_controller_questionsTemplateAdd' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/questions-template/add',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'QuestionsTemplate',
                Router::CONFIG_KEY_ACTION => 'add',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'question_controller_questions_getAll' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard/questions',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'QuestionsTemplate',
                Router::CONFIG_KEY_ACTION => 'getAll',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'login_controller_displayLogin' =>
            [
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Login',
                Router::CONFIG_KEY_ACTION => 'displayLogin',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'login_controller_login' =>
            [
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Login',
                Router::CONFIG_KEY_ACTION => 'login',
                Router::CONFIG_KEY_METHOD => 'POST'
            ],
        'login_controller_logout' =>
            [
                Router::CONFIG_KEY_PATH => '/logout',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Login',
                Router::CONFIG_KEY_ACTION => 'logout',
                Router::CONFIG_KEY_METHOD => 'GET'
            ],
        'admin_controller_dashboard' =>
            [
                Router::CONFIG_KEY_PATH => '/dashboard',
                Router::CONFIG_KEY_CONTROLLER_NAME => 'Admin',
                Router::CONFIG_KEY_ACTION => 'showDashBoard',
                Router::CONFIG_KEY_METHOD => 'GET'
            ]
    ]
];