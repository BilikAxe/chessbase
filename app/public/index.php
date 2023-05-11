<?php

include '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use banana\App;
use banana\Container;
use banana\Controllers\UserController;
use banana\Repository\UserRepository;


$container = new Container();

$container->set(UserController::class, function (Container $container){
    $userRepository = $container->get(UserRepository::class);

    return new UserController($userRepository);
});


$container->set(UserRepository::class, function (){
    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    return new UserRepository($connection);
});


$app = new App($container);

$app->get('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->get('/signin', [\banana\Controllers\UserController::class, 'signIn']);
$app->get('/main', [\banana\Controllers\MainController::class, 'main']);
$app->get('/error', [\banana\Controllers\ErrorController::class, 'error']);

$app->post('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->post('/signin', [\banana\Controllers\UserController::class, 'signIn']);


$app->run();