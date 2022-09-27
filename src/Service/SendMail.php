<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use Symfony\Component\Security\Core\User\UserInterface;
use function file_exists;
use function intval;
use function sleep;

use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

class SendMail
{
    private Swift_Mailer $mailer;
    private Environment $templating;
    private ContainerInterface $container;

    public function __construct(Swift_Mailer $mailer, Environment $templating, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->container = $container;
    }

    public function send(Order $order, UserInterface $user): void
    {
        $pdfFilepath = $this->container->getParameter('app.invoice_dir').'/'.$order->getInvoiceNo().'.pdf';

        $chkFile = intval(file_exists($pdfFilepath));
        $chkLoop = 0;
        while (0 === $chkFile) {
            $chkFile = intval(file_exists($pdfFilepath));
            if (0 === $chkFile) {
                sleep(1);
                ++$chkLoop;
                if (10 === $chkLoop) {
                    throw new Exception('Something went wrong!!');
                }
            }
        }
        $attFile = new Swift_Attachment();
        $message = (new Swift_Message('Invoice : '.$order->getInvoiceNo()))
            ->setFrom($this->container->getParameter('mailer.email_from'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'mailler/index.html.twig'
                )
            )
            ->attach($attFile::fromPath($pdfFilepath, 'application/pdf'));
        $this->mailer->send($message);
    }
}
