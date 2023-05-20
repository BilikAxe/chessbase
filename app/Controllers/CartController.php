<?php

namespace banana\Controllers;

use banana\Entity\Cart;
use banana\Entity\CartProduct;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\ProductRepository;
use banana\ViewRenderer;
use PDO;


class CartController
{
    private CartProductsRepository $cartProductsRepository;
    private ProductRepository $productRepository;
    private CartRepository $cartRepository;
    private ViewRenderer $renderer;
    private PDO $connection;

    public function __construct(
        CartProductsRepository $cartProductsRepository,
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        ViewRenderer $renderer,
        PDO $connection,
    )
    {
        $this->cartProductsRepository = $cartProductsRepository;
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->renderer = $renderer;
        $this->connection = $connection;
    }


    public function openCart(): ?string
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $cartProducts = $this->cartProductsRepository->getByUser($_SESSION['id']);

            return $this->renderer->render(
                '../Views/cart.phtml',
                [
                    'cartProducts' => $cartProducts
                ],
                true);
        }

        return null;
    }


    public function addToCart(): ?string
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
                    
                    $this->connection->beginTransaction();

                    try {
                        if (empty($cart)){
                            $cart = new Cart($userId);
                            $this->cartRepository->save($cart);
                        }

                        if (empty($cartProduct)) {
                            $product = $this->productRepository->getProduct($productId);
                            $cartProduct = new CartProduct($product, $cart, 0);
                        }

                        $this->cartProductsRepository->save($cartProduct);

                    } catch (\Throwable) {
                        $this->connection->rollBack();
                    }

                    $this->connection->commit();

                    header("Location: /category/$categoryId");
                    die;
                }
            }
        }


        return $this->renderer->render(
            "../Views/category/$categoryId",
            ['errorMessage' => $errorMessage],
            true,
        );

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