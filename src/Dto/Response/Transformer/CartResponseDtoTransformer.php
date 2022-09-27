<?php
declare(strict_types=1);

namespace App\Dto\Response\Transformer;

use App\Dto\Response\CartResponseDto;
use App\Entity\Cart;

class CartResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    public function transformFromObject($object): CartResponseDto
    {
        $dto = new CartResponseDto();
        $dto->id = $object->getId();
        $dto->product_id = $object->getProduct()->getId();
        $dto->product_name = $object->getProduct()->getName();
        $dto->quantity = $object->getQuantity();
        $dto->unit_price = $object->getProduct()->getUnitPrice();
        $dto->total = $object->getProduct()->getUnitPrice() * $object->getQuantity();

        return $dto;
    }

}