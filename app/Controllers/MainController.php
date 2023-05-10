<?php

namespace banana\Controllers;

class MainController
{
    public function main(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {
            return [
                './views/main.phtml',
                ['errorMessages'],
                true];
        }

        return [
            './views/signin.phtml',
            ['errorMessages'],
            false
        ];
    }
}