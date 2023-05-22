<?php

namespace banana\Services;

use banana\Entity\Cart;
use banana\Entity\CartProduct;
use banana\Entity\Product;
use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use PDO;

class CartService
{
    private PDO $connection;
    private CartRepository $cartRepository;
    private CartProductsRepository $cartProductsRepository;


    public function __construct(
        PDO $connection,
        CartRepository $cartRepository,
        CartProductsRepository $cartProductsRepository
    ) {
        $this->connection = $connection;
        $this->cartRepository = $cartRepository;
        $this->cartProductsRepository = $cartProductsRepository;
    }


    public function getCart(int $userId): Cart
    {
        $cart = $this->cartRepository->getByUser($userId);

        if (empty($cart)) {
            $cart = new Cart($userId);
            $this->cartRepository->save($cart);
        }

        return $cart;
    }


    public function addProduct(int $userId, Product $product): void
    {
        $this->connection->beginTransaction();

        try {
            $cart = $this->getCart($userId);
            $productId = $product->getId();
            $cartProduct = $this->cartProductsRepository->getOne($productId, $userId);

            if (empty($cartProduct)) {
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