<?php

$requestUri = $_SERVER['REQUEST_URI'];

$handler = route($requestUri);

list($view, $param) = require_once $handler;

ob_start();
require_once $view;
$start = ob_get_clean();

extract($param);

$content = file_get_contents('./views/layout.html');

$content = str_replace('{content}', $start, $content);

echo $content;


function route(string $Uri): string
{
    if (preg_match("#/(?<route>[a-z0-9-_]+)#", $Uri, $params)){
        if (file_exists("./handler/{$params['route']}.php")) {
            return "./handler/{$params['route']}.php";
        }
    }

    return './views/err.html';
}
