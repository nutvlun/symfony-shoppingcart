<?php
declare(strict_types=1);

namespace App\Dto\Response\Transformer;

use App\Dto\Response\OrderProductResponseDto;
use App\Entity\OrderProduct;

class OrderProductResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    public function transformFromObject($object): OrderProductResponseDto
    {

        $dto = new OrderProductResponseDto();
        $dto->id = $object->getId();
        $dto->product_id = $object->getProduct()->getId();
        $dto->product_name = $object->getProduct()->getName();
        $dto->quantity = $object->getQuantity();
        $dto->unit_price = $object->getProduct()->getUnitPrice();
        $dto->total = $object->getProduct()->getUnitPrice() * $object->getQuantity();

        return $dto;
    }

}

