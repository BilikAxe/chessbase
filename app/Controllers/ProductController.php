<?php

namespace banana\Controllers;

use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\ProductRepository;
use banana\ViewRenderer;

class ProductController
{
    private ProductRepository $productRepository;
    private CartRepository $cartRepository;
    private CartProductsRepository $cartProductsRepository;
    private ViewRenderer $renderer;


    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        CartProductsRepository $cartProductsRepository,
        ViewRenderer $renderer,
    )
    {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->cartProductsRepository = $cartProductsRepository;
        $this->renderer = $renderer;
    }

    public function getProductsByCategory(int $categoryId): ?string
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $products = $this->productRepository->getProductsByCategory($categoryId);
            $cart = $this->cartRepository->getByUser($_SESSION['id']);
            $quantity = $this->cartProductsRepository->getQuantityByCart($cart->getId());

            return $this->renderer->render(
                '../Views/categoryProducts.phtml',
                [
                    'products' => $products,
                    'categoryId' => $categoryId,
                    'quantity' => $quantity,
                ],
                true);
        }

        header("Location: /signin");
        die;
    }
}