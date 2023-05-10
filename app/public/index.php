<?php

include '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use banana\App;

$app = new App();

$app->get('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->get('/signin', [\banana\Controllers\UserController::class, 'signIn']);
$app->get('/main', [\banana\Controllers\MainController::class, 'main']);
$app->get('/error', [\banana\Controllers\ErrorController::class, 'error']);

$app->post('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->post('/signin', [\banana\Controllers\UserController::class, 'signIn']);


$app->run();