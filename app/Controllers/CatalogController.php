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

        if (isset($_SESSION['id'])) {

            $categories = $this->productRepository->getCategories();

            return [
                '../Views/catalog.phtml',
                [
                    'categories' => $categories
                ],
                true];
        }

        header("Location: /signin");
        return [];
    }


    public function openLaptop(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $products = $this->productRepository->getAllProducts();

            return [
                '../Views/laptop.phtml',
                [
                    'products' => $products,
                ],
                true];
        }

        header("Location: /signin");
        return [];
    }


    public function openPhone(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $products = $this->productRepository->getAllProducts();

            return [
                '../Views/phone.phtml',
                [
                    'products' => $products,
                ],
                true];
        }

        header("Location: /signin");
        return [];
    }


    public function openTV(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $products = $this->productRepository->getAllProducts();

            return [
                '../Views/tv.phtml',
                [
                    'products' => $products,
                ],
                true];
        }

        header("Location: /signin");
        return [];
    }
}