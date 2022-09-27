<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{

    private UserPasswordHasherInterface $passwordHasher;
    private  UserRepository $userRepository;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function create(Request $request): User
    {
        $user = new User();
        $user->setUsername($request->get('username'));
        $user->setPassword($request->get('password'));
        $user->setDisplayName($request->get('display_name'));
        $user->setEmail($request->get('email'));
        return $user;
    }

    public function persist(User $user): void
    {
        $hashPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashPassword);
        $this->userRepository->add($user);
    }
}
