<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getOrderTotal(int $id): ?int
    {
        $order = $this->orderRepository->find($id);

        if ($order === null) {
            return null;
        }

        return $order->getTotal();
    }

    public function createOrder(int $total, string $customerName): Order
    {
        $order = new Order($customerName, $total);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function getAllOrders(): array
    {
        return $this->orderRepository->findAll();
    }
}