<?php

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/signin') {
    require_once './handler/signin.php';

} elseif ($requestUri === '/signup') {
    require_once './handler/signup.php';

} elseif ($requestUri === '/main') {
    require_once './handler/main.php';

} elseif ($requestUri === '/error') {
    require_once './forms/err.phtml';
}



