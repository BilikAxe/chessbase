<?php

namespace banana\Controllers;

use banana\Repository\ProductRepository;

class CartController
{
    private ProductRepository $productRepository;


    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function openCart(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $cartProducts = $this->productRepository->getProductByUser($_SESSION['id']);

            return [
                '../Views/cart.phtml',
                ['cartProducts' => $cartProducts],
                true];
        }

        return [];
    }


    public function addToCart(int $categoryId): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//            print_r($_POST);die;


//            header("Location: /category/$categoryId");
//            die;



        }



    }

}