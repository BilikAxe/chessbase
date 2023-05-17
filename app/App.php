<?php

namespace banana;

use banana\Controllers\ErrorController;


class App
{
    private Container $container;

    private array $routes = [
        'GET' => [],
        'POST' => []
    ];


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function run(): void
    {
        try {
            $handler = $this->route();

            list($handler, $params) = $handler;

            if (is_array($handler)) {

                list($obj, $method) = $handler;

                if (!is_object($obj)) {
                    $obj = $this->container->get($obj);
                }

                if (empty($params)) {
                    $response = $obj->$method();
                } else {
                    $params = array_values($params);
                    $response = $obj->$method(...$params);
                }

            } else {
                $response = $handler();
            }

            list($view, $param, $isLayout) = $response;

            extract($param);

            ob_start();

            require_once $view;

            if ($isLayout) {

                $start = ob_get_clean();

                $content = file_get_contents('../Views/layout.html');

                $result = str_replace('{content}', $start, $content);

                echo $result;
            }
        } catch (\Throwable $exception) {

            $logger = $this->container->get(LoggerInterface::class);

            $data = [
                'Message' => $exception->getMessage(),
                'File' => $exception->getFile(),
                'Line' => $exception->getLine(),
            ];

            $logger->error('An error occurred while processing the request', $data);

            require_once '../Views/error500.html';
        }
    }

    private function route(): callable|array
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $pattern => $handler) {

            $params = [];

            if (preg_match("#^$pattern$#", $requestUri, $params)) {

                foreach ($params as $key => $value) {
                    if ($key === 0 || intval($key)) {
                        unset($params[$key]);
                    }
                }

                return [
                    $handler,
                    $params,
                ];
            }
        }

        return [[ErrorController::class, 'error'], null];
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
