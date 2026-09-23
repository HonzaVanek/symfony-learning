<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findAllWithCustomer(): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.customer', 'c')
            ->addSelect('c')
            ->getQuery()
            ->getResult();
    }
}