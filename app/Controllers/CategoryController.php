<?php

namespace banana\Controllers;

use banana\Repository\CategoryRepository;


class CategoryController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function openCatalog(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $categories = $this->categoryRepository->getCategories();

            return [
                '../Views/category.phtml',
                [
                    'categories' => $categories
                ],
                true];
        }

        header("Location: /signin");
        return [];
    }
}