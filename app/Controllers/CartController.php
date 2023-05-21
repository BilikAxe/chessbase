<?php

namespace banana\Controllers;

use banana\Repository\CartProductsRepository;
use banana\Repository\CartRepository;
use banana\Services\CartService;
use banana\ViewRenderer;
use Throwable;


class CartController
{
    private CartProductsRepository $cartProductsRepository;
    private ViewRenderer $renderer;
    private CartService $cartService;
    private CartRepository $cartRepository;


    public function __construct(
        CartProductsRepository $cartProductsRepository,
        ViewRenderer $renderer,
        CartService $cartService,
        CartRepository $cartRepository,
    )
    {
        $this->cartProductsRepository = $cartProductsRepository;
        $this->renderer = $renderer;
        $this->cartService = $cartService;
        $this->cartRepository = $cartRepository;
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


    /**
     * @throws Throwable
     */
    public function addToCart(): void
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        if (isset($_SESSION['id'])) {

            $categoryId = $_POST['categoryId'];

            $errorMessage = $this->validate($categoryId);

            if (empty($errorMessage)) {
                $userId = $_SESSION['id'];
                $productId = $_POST['productId'];

                $cartProduct = $this->cartProductsRepository->getOne($productId, $userId);
                $cart = $this->cartRepository->getByUser($userId);

                $this->cartService->addProduct($cart, $cartProduct);

                header("Location: /category/$categoryId");
                die;
            }
        }
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