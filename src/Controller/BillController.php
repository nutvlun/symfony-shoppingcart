<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BillController extends AbstractController
{
    private TokenStorageInterface $tokenStorage;
    private OrderRepository $orderRepository;

    public function __construct(TokenStorageInterface $token , OrderRepository $orderRepository)
    {
        $this->tokenStorage = $token;
        $this->orderRepository = $orderRepository;
    }

    public function listOrder(ManagerRegistry $doctrine): Response
    {
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $billData = $this->orderRepository->findBy([
            'userId' => $userId,
        ]);

        return $this->json($billData, 200, [], [
            'groups' => [
                'show_bill',
            ],
        ]);
    }

    public function showPdf(ManagerRegistry $doctrine, Order $order): Response
    {
        $invoiceNo = $order->getInvoiceNo();

        return new BinaryFileResponse($this->container->getParameter('app.invoice_dir').'/'.$invoiceNo.'.pdf');
    }
}
