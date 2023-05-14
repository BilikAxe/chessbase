<?php

namespace banana\Controllers;

class CartController
{
    public function openCart(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {
            return [
                '../Views/catalog.phtml',
                [],
                true];
        }

        return [
            '../Views/signin.phtml',
            [],
            false
        ];
    }
}