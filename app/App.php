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
        $handler = $this->route();

        if (is_array($handler)) {

            list($obj, $method) = $handler;

            if (!is_object($obj)) {
                try {
                    $obj = $this->container->get($obj);

                } catch (Exception\ExceptionContainer $e) {
                    echo $e->getMessage();
                }
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
