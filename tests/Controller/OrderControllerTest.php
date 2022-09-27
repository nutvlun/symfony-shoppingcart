<?php

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class OrderControllerTest extends ApiTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'test', $password = '12345')
    {
        $user = ['json' => [
            'username' => $username,
            'password' => $password,
        ]];
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            $user
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        // $client->setServerParameter(['Authorization'=> 'Bearer {'.$data['token'].'}']);

        // return $client;
        return $data['token'];
    }

    /**
     * test getPagesAction.
     */
    public function testInCart()
    {
        $token = $this->createAuthenticatedClient();

        $headers = [
            'auth_bearer' => $token,
        ];

        $client = static::createClient();
        $client->request('GET', '/incart', $headers);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['Order' => [], 'Total' => 0]);
    }

    public function testAddOrder(): void
    {
        $token = $this->createAuthenticatedClient();
        $arr_products = [1, 2, 3, 4];
        $client = static::createClient();
        foreach ($arr_products as $product) {
            $data = [
                'auth_bearer' => $token,
                'json' => [
                    'product_id' => $product,
                    'quantity' => 1,
                ],
            ];
            $client->request('POST', '/incart/add', $data);
            $this->assertResponseIsSuccessful();
            $this->assertJsonContains(['response' => 'success', 'message' => 'Add Order Complete!!!']);
        }
        // Check if product_id equal 0
        $data = [
            'auth_bearer' => $token,
            'json' => [
                'product_id' => 0,
                'quantity' => 1,
            ],
        ];
        $client->request('POST', '/incart/add', $data);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'error']);

        // Check if product_id not exist
        $data = [
            'auth_bearer' => $token,
            'json' => [
                'product_id' => 5,
                'quantity' => 1,
            ],
        ];
        $client->request('POST', '/incart/add', $data);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'error']);

        // Check if product_id is exist in order
        $data = [
            'auth_bearer' => $token,
            'json' => [
                'product_id' => 1,
                'quantity' => 1,
            ],
        ];
        $client->request('POST', '/incart/add', $data);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'error']);
    }

    public function testRemoveOrder()
    {
        $token = $this->createAuthenticatedClient();

        $arr_products = [1, 2];
        $client = static::createClient();
        foreach ($arr_products as $product) {
            $data = [
                'auth_bearer' => $token,
                'json' => [
                    'product_id' => $product,
                ],
            ];
            $client->request('POST', '/incart/remove', $data);
            $this->assertResponseIsSuccessful();
            $this->assertJsonContains(['response' => 'success', 'message' => 'Remove order Complete!!!']);
        }

        foreach ($arr_products as $product) {
            $data = [
                'auth_bearer' => $token,
                'json' => [
                    'product_id' => $product,
                ],
            ];
            $client->request('POST', '/incart/remove', $data);
            $this->assertResponseIsSuccessful();
            $this->assertJsonContains(['response' => 'error', 'message' => 'This order not exited!!!']);
        }
    }

    public function testCheckout()
    {
        $token = $this->createAuthenticatedClient();
        $data = [
            'auth_bearer' => $token,
            'json' => [
                'action' => 'checkout',
            ],
        ];
        $client = static::createClient();
        $client->request('POST', '/incart/checkout', $data);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'success', 'message' => 'Check out Complete!!!']);

        $client->request('POST', '/incart/checkout', $data);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'error', 'message' => 'No Data!!!']);
    }
}
