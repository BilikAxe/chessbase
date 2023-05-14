<?php

namespace banana\Controllers;

use banana\Repository\ProductRepository;
use http\Header;

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

        if (isset($_SESSION['id'])) {

            $products = $this->productRepository->getAllProducts();

            return [
                '../Views/catalog.phtml',
                ['products' => $products],
                true];
        }

        header("Location: /signin");
        return [];
    }
}