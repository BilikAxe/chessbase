<?php

namespace banana\Controllers;

use banana\Entity\Cart;
use banana\Entity\CartProducts;
use banana\Repository\CartProductsRepository;
use banana\Repository\ProductRepository;
use banana\Repository\UserRepository;


class CartController
{
    private CartProductsRepository $cartProductsRepository;
    private ProductRepository $productRepository;
    private UserRepository $userRepository;

    public function __construct(CartProductsRepository $cartProductsRepository, ProductRepository $productRepository, UserRepository $userRepository)
    {
        $this->cartProductsRepository = $cartProductsRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }


    public function openCart(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $cartProducts = $this->cartProductsRepository->getByUser($_SESSION['id']);

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
        $productId = $_POST['productId'];

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }


        if (isset($_SESSION['id'])) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $errorMessage = $this->validate($categoryId);

                if (empty($errorMessage)) {

                    $cartId = $this->userRepository->getCartId($_SESSION['id']);
                    $cartProduct = $this->cartProductsRepository->getOne($productId);

                    if ($cartProduct) {
                        $quantity = $cartProduct->getQuantity();
                        $quantity++;
                        $cartProduct->setQuantity($quantity);
                        $this->cartProductsRepository->updateQuantity($cartProduct);
                    } else {
//                        echo "<pre>" . print_r($cartId, true) . "</pre>";die;
                        $product = $this->productRepository->getProduct($productId);
                        $cart = new Cart($_SESSION['id']);
                        $cart->setCartId($cartId);
                        $quantity = 1;
                        $cartProduct = new CartProducts($product, $cart, $quantity);
                        $this->cartProductsRepository->save($cartProduct);
                    }


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