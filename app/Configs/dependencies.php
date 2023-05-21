<?php

use banana\Container;
use banana\Controllers\CartController;
use banana\Controllers\CategoryController;
use banana\Controllers\ErrorController;
use banana\Controllers\ProductController;
use banana\Controllers\UserController;
use banana\FileLogger;
use banana\LoggerInterface;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\CategoryRepository;
use banana\Repository\ProductRepository;
use banana\Repository\UserRepository;
use banana\Services\CartService;
use banana\ViewRenderer;


return [
    UserController::class => function (Container $container) {
        $userRepository = $container->get(UserRepository::class);
        $renderer = $container->get(ViewRenderer::class);
        $cartRepository = $container->get(CartRepository::class);

        return new UserController($userRepository, $renderer, $cartRepository);
    },


    UserRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new UserRepository($connection);
    },


    ProductController::class => function (Container $container) {
        $productRepository = $container->get(ProductRepository::class);
        $cartRepository = $container->get(CartRepository::class);
        $cartProductRepository = $container->get(CartProductsRepository::class);
        $renderer = $container->get(ViewRenderer::class);

        return new ProductController($productRepository, $cartRepository, $cartProductRepository, $renderer);
    },


    ProductRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new ProductRepository($connection);
    },


    CategoryController::class => function (Container $container) {
        $categoryRepository = $container->get(CategoryRepository::class);
        $cartRepository = $container->get(CartRepository::class);
        $cartProductRepository = $container->get(CartProductsRepository::class);
        $renderer = $container->get(ViewRenderer::class);

        return new CategoryController($categoryRepository, $cartRepository, $cartProductRepository, $renderer);
    },


    CategoryRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CategoryRepository($connection);
    },


    CartController::class => function (Container $container) {
        $cartProductRepository = $container->get(CartProductsRepository::class);
        $renderer = $container->get(ViewRenderer::class);
        $cartService = $container->get(CartService::class);
        $cartRepository = $container->get(CartRepository::class);

        return new CartController($cartProductRepository, $renderer, $cartService, $cartRepository);
    },


    CartProductsRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CartProductsRepository($connection);
    },


    CartRepository::class => function (Container $container) {
        $connection = $container->get('db');

        return new CartRepository($connection);
    },


    ErrorController::class => function (Container $container) {
        $renderer = $container->get(ViewRenderer::class);

        return new ErrorController($renderer);
    },


    LoggerInterface::class => function () {

        return new FileLogger();
    },


    CartService::class => function (Container $container) {
        $connection = $container->get('db');
        $cartRepository = $container->get(CartRepository::class);
        $productRepository = $container->get(ProductRepository::class);
        $cartProductRepository = $container->get(CartProductsRepository::class);

        return new CartService($connection, $cartRepository, $productRepository, $cartProductRepository);
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