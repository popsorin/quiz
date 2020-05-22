<?php

use Framework\Application;
use Framework\Http\Request;
ini_set("display_errors",1);

require __DIR__ . "/../vendor/autoload.php";

$container = require __DIR__.'/../config/services.php';
// create the application and handle the request
$application = Application::create($container);
$request = Request::createFromGlobals();
$response = $application->handle($request);

$response->send();