<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\CartRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Exception\NotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class CreateInvoiceDataService
{
    private CartRepository $cartRepository;
    private OrderProductRepository $orderProductRepository;
    private OrderRepository $orderRepository;

    public function __construct(CartRepository $cartRepository,OrderRepository $orderRepository,OrderProductRepository $orderProductRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRepository = $orderRepository;
    }
   
    public function create(UserInterface $user): Order
    {
        $order = new Order();
        $cart = $this->cartRepository->findCartByUserId($user->getId());
        if (!$cart) {
            throw new NotFoundException('Do not have data !!!');
        }

        $order->setUserId($user->getId());
        $order->setUsername($user->getUserIdentifier());
        $order->setInvoiceNo($this->orderRepository->createRandomInvoiceNumber());
        $this->orderRepository->add($order);


        foreach ($cart as $cartItem) {
            $orderProduct = new OrderProduct();
            $orderProduct->setOrderId($order->getId());
            $orderProduct->setProduct($cartItem->getProduct());
            $orderProduct->setUnitPrice(floatval($cartItem->getUnitPrice()));
            $orderProduct->setQuantity($cartItem->getQuantity());
            $this->orderProductRepository->add($orderProduct);
            $this->cartRepository->remove($cartItem);
        }

        return $order;
    }
}
