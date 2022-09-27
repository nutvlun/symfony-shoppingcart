<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateOrderService
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function add(UserInterface $user, Request $request): Cart
    {
        $productExist = $this->cartRepository->findCartByUserIdAndProductId($user->getId(), $request->get('product_id'));
        if($productExist) {
            throw new \Exception('The product_id is duplicate in cart.');
        }
        $cart = new Cart();
        $cart->setUserId($user->getId());
        $cart->setProductId($request->get('product_id'));
        $cart->setQuantity($request->get('quantity'));
        return $cart;
    }

    public function remove(UserInterface $user, Request $request): void
    {
        $cart = $this->cartRepository->findCartByUserIdAndProductId($user->getId(), $request->get('product_id'));
        if (!$cart) {
            throw new NotFoundException('This order not exited!!!');
        }
        $this->cartRepository->remove($cart);
    }

    public function persist(Cart $cart): void
    {
        $product = $this->productRepository->findProductById($cart->getProductId());
        if (!$product) {
            throw new NotFoundException('The product_id is not exist.');
        }

        $cart->setProduct($product);
        $this->cartRepository->add($cart);
    }
}
