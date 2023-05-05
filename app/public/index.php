<?php

$requestUri = $_SERVER['REQUEST_URI'];


redirectUri($requestUri);


function redirectUri(string $rUri)
{
    if (preg_match("#/(?<route>[a-z0-9-_]+)#", $rUri, $params)){
        if (isset($params)) {
            if (file_exists("./handler/{$params['route']}.php")) {
                return require_once "./handler/{$params['route']}.php";
            }
        }
    }

    return require_once './views/err.phtml';
}
