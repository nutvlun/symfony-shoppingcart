<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Response\Transformer\CartResponseDtoTransformer;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use function array_reduce;

use function floatval;
use function number_format;

class CartViewService
{
    private CartRepository $cartRepository;
    private CartResponseDtoTransformer $cartResponseDtoTransformer;
    public ArrayCollection $cartItems;

    public function __construct(CartRepository $cartRepository, CartResponseDtoTransformer $cartResponseDtoTransformer)
    {
        $this->cartRepository = $cartRepository;
        $this->cartResponseDtoTransformer = $cartResponseDtoTransformer;
        $this->cartItems = new ArrayCollection();
    }

    public function show(UserInterface $user): CartViewService
    {
<<<<<<< HEAD
        $carts = $this->cartRepository->findCartByUserId($user->getId());
        $cartDatas = array();
=======
        $carts = $this->cartRepository->findBy(['userId' => $user->getId()]);
        $cartDatas = Array();
>>>>>>> 85ce467e0c77ce9042d3f7b6e7c260e94e1ce1df
        foreach ($carts as $cart) {
            $cartDatas[] = $this->cartResponseDtoTransformer->transformFromObject($cart);
        }

        $totalPrice = array_reduce($cartDatas, function ($carry, $item) {
            return $carry + floatval($item->total);
        }, 0);
        $totalPrice = number_format($totalPrice, 2);

        $this->cartItems->add(['cart' => $cartDatas, 'total_price' => $totalPrice]);
        return $this;
    }
}
