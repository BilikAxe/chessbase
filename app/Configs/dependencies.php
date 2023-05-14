<?php

use banana\Container;
use banana\Controllers\CatalogController;
use banana\Controllers\UserController;
use banana\FileLogger;
use banana\LoggerInterface;
use banana\Repository\ProductRepository;
use banana\Repository\UserRepository;


return [
    UserController::class => function (Container $container) {
        $userRepository = $container->get(UserRepository::class);

        return new UserController($userRepository);
    },


    UserRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new UserRepository($connection);
    },


    CatalogController::class => function (Container $container) {
        $productRepository = $container->get(ProductRepository::class);

        return new CatalogController($productRepository);
    },


    ProductRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new ProductRepository($connection);
    },


    LoggerInterface::class => function () {

        return new FileLogger();
    },


    'db' => function(Container $container) {
        $settings = $container->get('settings');
        $host = $settings['db']['host'];
        $name = $settings['db']['database'];
        $user = $settings['db']['username'];
        $password = $settings['db']['password'];

        return new PDO("pgsql:host={$host};dbname={$name}", "{$user}", "{$password}");
    }

];