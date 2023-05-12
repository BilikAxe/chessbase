<?php

namespace banana;

use banana\Controllers\ErrorController;


class App
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private array $routes = [
        'GET' => [],
        'POST' => []
    ];


    public function run(): void
    {
        try {
            $handler = $this->route();

            if (is_array($handler)) {

                list($obj, $method) = $handler;

                if (!is_object($obj)) {
                    $obj = $this->container->get($obj);
                }

                $response = $obj->$method();

            } else {
                $response = $handler();
            }

            list($view, $param, $isLayout) = $response;

            extract($param);

            ob_start();

            require_once $view;

            if ($isLayout) {

                $start = ob_get_clean();

                $content = file_get_contents('./views/layout.html');

                $result = str_replace('{content}', $start, $content);

                echo $result;
            }
        } catch (\Throwable $exception) {
            $errorFile = fopen('../Log/error.php', "w+");
            fputs($errorFile, "Message: {$exception->getMessage()}\n");
            fputs($errorFile, "File: {$exception->getFile()}\n");
            fputs($errorFile, "Line: {$exception->getLine()}\n");
            fclose($errorFile);

            require_once './views/error.html';
        }
    }

    private function route(): callable|array
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];


        foreach ($this->routes[$method] as $pattern => $handler) {
            if (preg_match("#^$pattern$#", $requestUri)) {

                return $handler;
            }
        }

        return [ErrorController::class, 'error'];
    }


    public function get(string $route, callable|array $callable): void
    {
        $this->routes['GET'][$route] = $callable;
    }


    public function post(string $route, callable|array $callable): void
    {
        $this->routes['POST'][$route] = $callable;
    }
}
