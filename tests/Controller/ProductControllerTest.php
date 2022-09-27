<?php

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ProductControllerTest extends ApiTestCase
{
    public function testProductIndex(): void
    {
        $response = static::createClient()->request('GET', '/product');

        $this->assertResponseIsSuccessful();
        // $this->assertJsonContains(['@id' => '/']);
    }

    public function testProductShowProduct(): void
    {
        $product_id = rand(1, 4);
        $response = static::createClient()->request('GET', '/product/'.$product_id);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['id' => $product_id]);

        $product_id = rand(5, 10);
        $response = static::createClient()->request('GET', '/product/'.$product_id);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'error']);
    }
}
