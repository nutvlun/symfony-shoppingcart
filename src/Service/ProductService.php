<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;
use function array_map;

class ProductService
{

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function showAll(): array
    {
        $products = $this->productRepository->findAll();
        $result = array_map(function ($product) {
            return [
                'product_id' => $product->getId(),
                'product_name' => $product->getName(),
                'unit_price' => $product->getUnitPrice(),
            ];
        }, $products);

        return $result;
    }
}
