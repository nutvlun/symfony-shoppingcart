<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Response\Transformer\OrderProductResponseDtoTransformer;
use App\Entity\Order;

use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use function array_reduce;

use Dompdf\Dompdf;
use Dompdf\Options;

use function file_put_contents;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class PdfGenerator
{
    private Environment $templating;
    private ContainerInterface $container;
    private OrderRepository $orderRepository;
    private OrderProductRepository $orderProductRepository;
    private OrderProductResponseDtoTransformer $orderProductResponseDtoTransformer;

    public function __construct(
        Environment $templating,
        ContainerInterface $container,
        OrderRepository $orderRepository,
        OrderProductRepository $orderProductRepository,
        OrderProductResponseDtoTransformer $orderProductResponseDtoTransformer
    ) {
        $this->templating = $templating;
        $this->container = $container;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderProductResponseDtoTransformer = $orderProductResponseDtoTransformer;
    }

    public function generatePdf(Order $order): void
    {
        $pdfDataHeader = $this->orderRepository->findOneById($order->getId());

        $orderProducts = $this->orderProductRepository->findByOrderId($order->getId());

        $pdfDataDetail = Array();
        foreach ($orderProducts as $orderProduct) {
           $pdfDataDetail[] = $this->orderProductResponseDtoTransformer->transformFromObject($orderProduct);
        }

        $totalPrice = array_reduce($pdfDataDetail, function ($carry, $item) {
            return $carry + floatval($item->total);
        }, 0);
        $totalPrice = number_format($totalPrice, 2);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->templating->render('bill/index.html.twig', [
            'header' => $pdfDataHeader,
            'datas' => $pdfDataDetail,
            'totalPrice' => $totalPrice,
        ]);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        $pdfFilepath = $this->container->getParameter('app.invoice_dir').'/'.$pdfDataHeader->getInvoiceNo().'.pdf';
        file_put_contents($pdfFilepath, $output);

    }
}
