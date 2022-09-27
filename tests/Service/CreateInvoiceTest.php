<?php

namespace Tests\App\Service;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateInvoiceTest extends WebTestCase
{
    public function testCreate()
    {
        $testUserObject = new User();
        $username = ['username' => 'test'];
        $userRepo = $this->createMock(UserRepository::class);
        $userRepo->expects($this->any())
            ->method('findOneBY')
            ->with($username)
            ->will($this->returnValue($testUserObject));
        echo "Test User...\n";
    }
}
