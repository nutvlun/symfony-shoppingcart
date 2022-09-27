<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CreateUserService;
use App\Service\LoginUserService;
use App\Controller\BaseController;
use App\Exception\NotFoundException;

use function count;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class UserController extends AbstractController
{
    private BaseController $baseController;
    public function __construct(BaseController $baseController)
    {
        $this->baseController = $baseController;
    }
    public function signup(
        Request $request,
        ValidatorInterface $validator,
        CreateUserService $createUserService
    ): Response {
        $user = $createUserService->create($request);
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->baseController->returnError((string) $errors);

        }

        $createUserService->persist($user);

        return $this->baseController->returnSuccess('User created successfully');
    }

    public function login(Request $request, LoginUserService $loginUserService): Response
    {
        try {
            $token = $loginUserService->login($request);
        } catch (NotFoundException $e) {
            return $this->baseController->returnError($e->getMessage());
        }
        return $this->baseController->returnSuccess('Login Success',['token'=>$token]);
    }
}
