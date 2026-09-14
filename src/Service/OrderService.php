<?php

namespace App\Service;

use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
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
}