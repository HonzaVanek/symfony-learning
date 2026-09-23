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

#    public function createOrder(int $total, string $customerName): Order
#    {
#        $order = new Order($customerName, $total);
#
#        $this->entityManager->persist($order);
#        $this->entityManager->flush();
#
#        return $order;
#    }

    public function getAllOrders(): array
    {
        return $this->orderRepository->findAllWithCustomer();
    }

    public function getOrder(int $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

#    public function updateOrder(
#        Order $order,
#        string $customerName,
#        int $total,
#    ): void {
#        $order->setCustomerName($customerName);
#        $order->setTotal($total);

#        $this->entityManager->flush();
#    }

    public function deleteOrder(Order $order): void
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }

    public function saveChanges(): void
    {
        $this->entityManager->flush();
    }

    public function saveNewOrder(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

}