<?php

include '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use banana\App;
use banana\Container;

$dependencies = require_once '../Configs/dependencies.php';
$configs = require_once '../Configs/settings.php';

$data = array_merge($configs, $dependencies);

$container = new Container($data);

$app = new App($container);

$app->get('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->get('/signin', [\banana\Controllers\UserController::class, 'signIn']);
$app->get('/catalog', [\banana\Controllers\CatalogController::class, 'openCatalog']);
$app->get('/error', [\banana\Controllers\ErrorController::class, 'error']);

$app->post('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->post('/signin', [\banana\Controllers\UserController::class, 'signIn']);


$app->run();