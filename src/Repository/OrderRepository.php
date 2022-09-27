<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Config\TwigExtra\StringConfig;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Order $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    private function randomInvoiceNumber(): String
    {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function invoiceExist(String $invoiceNo): bool
    {
        if($this->findOneBy(['invoiceNo'=>$invoiceNo])==NULL){
            return true;
        }else{
            return false;

        }
    }

    public function createRandomInvoiceNumber(): String
    {
        $invoiceNumber = $this->randomInvoiceNumber();
        while(!$this->invoiceExist($invoiceNumber)){
            $invoiceNumber = $this->randomInvoiceNumber();
        }

        return $invoiceNumber;
    }

    public function findById(int $orderId): ?Order
    {
        return $this->findOneBy(['id' => $orderId]);
    }
}
