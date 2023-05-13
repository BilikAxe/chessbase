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
                '../Views/main.phtml',
                ['errorMessages'],
                true];
        }

        return [
            '../Views/signin.phtml',
            ['errorMessages'],
            false
        ];
    }
}