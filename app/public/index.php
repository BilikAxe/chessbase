<?php

spl_autoload_register(function ($class) {

    $appRoot = dirname(__DIR__);

    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    $path = preg_replace('#^banana#', $appRoot, $path);

    if (file_exists($path)) {
        require_once $path;
        return true;
    }

    return false;
});

use banana\App;

$app = new App();

$app->addRoute('/signup', './handlers/signup.php');
$app->addRoute('/signin', './handlers/signin.php');
$app->addRoute('/main', './handlers/main.php');
$app->addRoute('/error', './handlers/error.php');

$app->run();