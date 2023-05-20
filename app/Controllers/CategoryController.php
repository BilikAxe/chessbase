<?php

namespace banana\Controllers;

use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Repository\CategoryRepository;
use banana\ViewRenderer;


class CategoryController
{
    private CategoryRepository $categoryRepository;
    private CartRepository $cartRepository;
    private CartProductsRepository $cartProductsRepository;
    private ViewRenderer $renderer;

    public function __construct(
        CategoryRepository $categoryRepository,
        CartRepository $cartRepository,
        CartProductsRepository $cartProductsRepository,
        ViewRenderer $renderer,
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->cartRepository = $cartRepository;
        $this->cartProductsRepository = $cartProductsRepository;
        $this->renderer = $renderer;
    }

    public function openCatalog(): ?string
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $cart = $this->cartRepository->getByUser($_SESSION['id']);

            if ($cart){
                $categories = $this->categoryRepository->getCategories();
                $quantity = $this->cartProductsRepository->getQuantityByCart($cart->getId());

                return $this->renderer->render(
                    '../Views/categories.phtml',
                    [
                        'categories' => $categories,
                        'quantity' => $quantity,
                    ],
                    true
                );
            }
        }

        header("Location: /signin");
        die;
    }
}