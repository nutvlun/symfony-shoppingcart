<?php
declare(strict_types=1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
class CartResponseDto
{
    /**
     * @Serialization\Type("integer")
     */
    public int $id;

    /**
     * @Serialization\Type("integer")
     */
    public int $product_id;

    /**
     * @Serialization\Type("string")
     */
    public string $product_name;

    /**
     * @Serialization\Type("integer")
     */
    public int $quantity;

    /**
     * @Serialization\Type("float")
     */
    public float $unit_price;

    /**
     * @Serialization\Type("float")
     */
    public float $total;

}