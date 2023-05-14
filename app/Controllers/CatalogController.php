<?php

namespace banana\Controllers;

use banana\Repository\ProductRepository;

class CatalogController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function openCatalog(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $products = [];

        if (isset($_SESSION['id'])) {
            return [
                '../Views/catalog.phtml',
                ['products' => $products],
                true];
        }

        return [
            '../Views/signin.phtml',
            [],
            false
        ];
    }
}