<?php

include '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use banana\App;
use banana\Container;
use banana\Controllers\UserController;
use banana\KeyValueStorage;
use banana\Repository\UserRepository;

$storage = new KeyValueStorage();

//$storage->set('key1', 'abcd');
//echo $storage->get('key1');die;

//$storage->set('key1', function (){
//    echo 'Hello word';
//});
//$storage->get('key1')();die;

//$value = $storage->get('key1');
//$value();die;

$container = new Container();

$container->set(UserController::class, function (){

});

$container->set(UserRepository::class, function (){

});







$app = new App();

$app->get('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->get('/signin', [\banana\Controllers\UserController::class, 'signIn']);
$app->get('/main', [\banana\Controllers\MainController::class, 'main']);
$app->get('/error', [\banana\Controllers\ErrorController::class, 'error']);

$app->post('/signup', [\banana\Controllers\UserController::class, 'signUp']);
$app->post('/signin', [\banana\Controllers\UserController::class, 'signIn']);


$app->run();