<?php

namespace banana\Controllers;

use banana\Entity\CartProducts;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\ProductRepository;
use banana\Repository\UserRepository;

class CartController
{
    private CartProductsRepository $cartProductsRepository;
    private ProductRepository $productRepository;
    private UserRepository $userRepository;
    private CartRepository $cartRepository;


    public function __construct(ProductRepository $productRepository, CartProductsRepository $cartProductsRepository, UserRepository $userRepository, CartRepository $cartRepository)
    {
        $this->cartProductsRepository = $cartProductsRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->cartRepository = $cartRepository;
    }


    public function openCart(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $cartProducts = $this->cartProductsRepository->getProductsByUser($_SESSION['id']);

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
        if (isset($_SESSION['id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $errorMessage = $this->validate($_POST['productId']);
//                echo "<pre>" . print_r($_POST, true) . "</pre>";die;

                if (empty($errorMessage)) {

                    $product = $this->productRepository->getProduct($_POST['productId']);
                    $cartId = $this->userRepository->getUserByUserId($_SESSION['id'])->getCartId();
                    $cart = $this->cartRepository->getCartByCartId($cartId);
                    $quantity = $this->cartProductsRepository->getQuantity($_POST['productId']);
                    $products = $this->cartProductsRepository->getProductsByUser($_SESSION['id']);
//                    echo "<pre>" . print_r($products, true) . "</pre>";die;
                    if (in_array($product, $products)) {
                        $quantity++;
                        $this->cartProductsRepository->addQuantity($quantity, $_POST['productId']);
                    }

                    $cartProduct = new CartProducts($product, $cart, $quantity);
                    $this->cartProductsRepository->saveProductInCart($cartProduct);


                    header("Location: /category/$categoryId");
                    die;
                }
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