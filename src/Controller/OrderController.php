<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CreateInvoiceDataService;
use App\Service\CreateOrderService;
use App\Service\CartViewService;
use App\Service\PdfGenerator;
use App\Service\SendMail;
use App\Controller\BaseController;
use App\Exception\NotFoundException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{
    private TokenStorageInterface $tokenStorage;
    private BaseController $baseController;

    public function __construct(TokenStorageInterface $tokenStorage, BaseController $baseController)
    {
        $this->tokenStorage = $tokenStorage;
        $this->baseController = $baseController;
    }

    public function inCart(CartViewService $cartViewService): Response
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $this->json(
            $cartViewService->show($user)->cartItems->toArray()
        );
    }

    public function addOrder(
        Request $request,
        ValidatorInterface $validator,
        CreateOrderService $createOrderService
    ): Response {

        $user = $this->tokenStorage->getToken()->getUser();
        try{
            $cart = $createOrderService->add($user, $request);
        } catch (\Exception $e) {
            return $this->baseController->returnError($e->getMessage());
        }
        $errors = $validator->validate($cart);

        if (count($errors) > 0) {
           return $this->baseController->returnError((string) $errors);
        }

        try{
            $createOrderService->persist($cart);
        }catch (NotFoundException $e) {
            return $this->baseController->returnError($e->getMessage());
        }
        return $this->baseController->returnSuccess('Order added successfully');
    }

    public function removeOrder(
        CreateOrderService $createOrderService,
        Request $request
    ): Response {
        $user = $this->tokenStorage->getToken()->getUser();

        try {
            $createOrderService->remove($user,$request);
        } catch (NotFoundException $e) {
            return $this->baseController->returnError($e->getMessage());
        }
        return $this->baseController->returnSuccess('Order removed successfully');

    }

    public function checkOut(
        CreateInvoiceDataService $createInvoiceDataService,
        Request                  $request,
        PdfGenerator             $pdf,
        SendMail                 $sendMail
    ): Response {
        $user = $this->tokenStorage->getToken()->getUser();
        if ('checkout' !== $request->get('action')) {
            return $this->baseController->returnError('Invalid action');
        }

        try {
            $order = $createInvoiceDataService->create($user);
        } catch (NotFoundException $e) {
            return $this->baseController->returnError($e->getMessage());
        }
        $pdf->generatePdf($order);
        try {
            $sendMail->send($order,$user);
        }catch (\Exception $e) {
            return $this->baseController->returnError($e->getMessage());
        }
        return $this->baseController->returnSuccess('Check out Complete!!!',['invoice_no' => $order->getInvoiceNo()]);
    }
}
