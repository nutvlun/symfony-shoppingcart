<?php

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testSignUp(): void
    {
        $header = [
            'Content-Type' => 'application/json',
        ];
        $user = ['json' => [
            'username' => 'test',
            'display_name' => 'test',
            'email' => 'test@email.com',
            'password' => '12345',
        ]];

        $response = static::createClient()->request('POST', '/user/signup', $user);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['response' => 'success']);

        $arr_users = ['tESt', 'Test', 'tEsT', 'tesT'];
        foreach ($arr_users as $arr_user) {
            $user = ['json' => [
                'username' => $arr_user,
                'display_name' => $arr_user,
                'email' => 'test@email.com',
                'password' => '12345',
            ]];

            $response = static::createClient()->request('POST', '/user/signup', $user);

            $this->assertResponseIsSuccessful();
            $this->assertJsonContains(['response' => 'error']);
        }
    }
    // public function testLogin(): void
    // {
    //     $header = [
    //         'Content-Type' => 'application/json',
    //     ];
    //     $arr_users   =   ['test','tESt','Test','tEsT','tesT'];
    //     $arr_passwords  =   ['12346','142563','password','556789'];
    //     foreach($arr_passwords as $password){
    //         $user =['json'=>[
    //             'username' => 'test',
    //             'password' => $password
    //         ]];
    //         $response = static::createClient()->request('POST','/api/login_check', $user);

    //         $this->assertResponseIsSuccessful();
    //         $this->assertJsonContains(['code' => '401']);
    //     }
    //     foreach($arr_users as $arr_user){
    //         $user =['json'=>[
    //             'username' => $arr_user,
    //             'password' => '12345'
    //         ]];
    //         $response = static::createClient()->request('POST','/api/login_check', $user);

    //         $this->assertResponseIsSuccessful();
    //         $this->assertJsonContains(['token' => $this->token()]);
    //     }

    // }
}
