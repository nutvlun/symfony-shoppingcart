<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LoginUserService
{
    private UserPasswordHasherInterface $passwordHasher;
    private JWTTokenManagerInterface $tokenManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $tokenManager,
        UserRepository $userRepository
    ) {

        $this->passwordHasher = $passwordHasher;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
    }

    public function login(Request $request): ?String
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            throw new NotFoundException('User not found !!!');
        }

        if ($this->passwordHasher->isPasswordValid($user, $password)) {
            $token = $this->tokenManager->create($user);

            return $token;
        }
        throw new NotFoundException('Invalid Username or Password');

    }
}
