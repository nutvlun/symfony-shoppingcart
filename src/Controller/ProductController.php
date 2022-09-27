<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_product")
     */
    public function index(ProductService $productService): Response
    {
        $result = $productService->showAll();

        return $this->json($result);
    }

    public function showProduct(Product $product): Response
    {
        return $this->json($product, 200, [], [
            'groups' => [
                'show_product',
            ],
        ]);
    }
}
