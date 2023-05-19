<?php

namespace banana\Controllers;

use banana\Entity\Cart;
use banana\Entity\CartProduct;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\ProductRepository;


class CartController
{
    private CartProductsRepository $cartProductsRepository;
    private ProductRepository $productRepository;
    private CartRepository $cartRepository;

    public function __construct(CartProductsRepository $cartProductsRepository, ProductRepository $productRepository, CartRepository $cartRepository)
    {
        $this->cartProductsRepository = $cartProductsRepository;
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
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

                    $userId = $_SESSION['id'];
                    $cartProduct = $this->cartProductsRepository->getOne($productId, $userId);
                    $cart = $this->cartRepository->getByUser($userId);

                    if (empty($cart)){
                        $cart = new Cart($userId);
                        $this->cartRepository->save($cart);
                    }

                    if (empty($cartProduct)) {
//                        echo "<pre>" . print_r($cartId, true) . "</pre>";die;
                        $product = $this->productRepository->getProduct($productId);
                        $quantity = 0;
                        $cartProduct = new CartProduct($product, $cart, $quantity);
                    }
//                    echo "<pre>" . print_r($cartProduct, true) . "</pre>";die;
                    $this->cartProductsRepository->save($cartProduct);

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