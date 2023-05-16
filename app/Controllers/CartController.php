<?php

namespace banana\Controllers;

use banana\Repository\CartRepository;

class CartController
{
    private CartRepository $cartRepository;


    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    public function openCart(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {
            return [
                '../Views/cart.phtml',
                [],
                true];
        }

        return [];
    }
}