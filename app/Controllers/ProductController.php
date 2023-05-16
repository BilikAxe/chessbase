<?php

namespace banana\Controllers;

use banana\Repository\ProductRepository;

class ProductController
{
    private ProductRepository $productRepository;


    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function openProduct(int $categoryId): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $products = $this->productRepository->getAllProducts($categoryId);

            return [
                '../Views/product.phtml',
                [
                    'products' => $products,
                ],
                true];
        }

        header("Location: /signin");
        return [];
    }
}