<?php

namespace banana\Services;

use banana\Entity\Cart;
use banana\Entity\CartProduct;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\ProductRepository;
use PDO;

class CartService
{
    private PDO $connection;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private CartProductsRepository $cartProductsRepository;


    public function __construct(PDO $connection, CartRepository $cartRepository, ProductRepository $productRepository, CartProductsRepository $cartProductsRepository)
    {
        $this->connection = $connection;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->cartProductsRepository = $cartProductsRepository;
    }


    public function addProduct(Cart $cart, CartProduct $cartProduct): void
    {
        $userId = $_SESSION['id'];
        $productId = $_POST['productId'];

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

        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }

        $this->connection->commit();
    }
}