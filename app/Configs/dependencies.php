<?php

use banana\Container;
use banana\Controllers\CartController;
use banana\Controllers\CatalogController;
use banana\Controllers\ProductController;
use banana\Controllers\UserController;
use banana\FileLogger;
use banana\LoggerInterface;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\CategoryRepository;
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


    ProductController::class => function (Container $container) {
        $productRepository = $container->get((ProductRepository::class));

        return new ProductController($productRepository);
    },


    ProductRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new ProductRepository($connection);
    },


    CatalogController::class => function (Container $container) {
        $categoryRepository = $container->get(CategoryRepository::class);

        return new CatalogController($categoryRepository);
    },


    CategoryRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CategoryRepository($connection);
    },


    CartController::class => function (Container $container) {
        $cartRepository = $container->get(CartRepository::class);

        return new CartController($cartRepository);
    },


    CartRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CartRepository($connection);
    },


    CartProductsRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CartProductsRepository($connection);
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