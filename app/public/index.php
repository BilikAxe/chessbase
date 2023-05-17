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
$app->get('/category', [\banana\Controllers\CategoryController::class, 'openCatalog']);
$app->get('/error', [\banana\Controllers\ErrorController::class, 'error']);
$app->get('/category/\b(?<categoryId>[0-9])\b', [\banana\Controllers\ProductController::class, 'openProduct']);
$app->get('/cart', [\banana\Controllers\CartController::class, 'openCart']);


$app->post('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->post('/signin', [\banana\Controllers\UserController::class, 'signIn']);
$app->post('/category', [\banana\Controllers\CategoryController::class, 'openCatalog']);


$app->run();