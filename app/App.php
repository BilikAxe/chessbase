<?php

namespace banana;

class App
{
    private array $routes = [];

    public function run(): void
    {
        $handler = $this->route();

        list($view, $param, $isLayout) = require_once $handler;

        ob_start();
        require_once $view;

        if ($isLayout) {

            $start = ob_get_clean();

            extract($param);

            $content = file_get_contents('./views/layout.html');

            $content = str_replace('{content}', $start, $content);

            echo $content;
        }
    }

    private function route(): ?string
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        if (isset($this->routes)) {
            foreach ($this->routes as $pattern => $handler) {
                if (preg_match("#$pattern#", $requestUri)) {
                    if (file_exists($handler)) {
                        return $handler;
                    }
                }
            }
        }

        return './handlers/error.php';
    }

    public function addRoute(string $route, string $handlerPath): void
    {
        $this->routes[$route] = $handlerPath;
    }
}
