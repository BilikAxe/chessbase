<?php

namespace banana\Controllers;

use banana\Entity\CartProducts;
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


    public function addToCart(): array
    {
        $errorMessage = [];
        $categoryId = $_POST['categoryId'];

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessage = $this->validate($_POST['productId']);

            if (empty($errorMessage)) {

                $cartProduct = new CartProducts($this->productRepository->getProduct($_POST['productId']));



                header("Location: /category/$categoryId");
                die;
            }
        }

        return [
            "../Views/category/$categoryId",
            ['errorMessage' => $errorMessage],
            true,
        ];

    }


    private function validate(int $productId): array
    {
        $errorMessage = [];

        if (empty($productId)) {
            $errorMessage['productId'] = 'Invalid productId';
        }

        return $errorMessage;
    }

}