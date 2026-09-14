<?php

namespace App\Repository;

use App\Entity\Order;

class OrderRepository
{
    private array $orders;

    public function __construct()
    {
        $this->orders = [
            new Order(1, 1500),
            new Order(2, 800),
            new Order(3, 3200),
        ];
    }

    public function find(int $id): ?Order
    {
        foreach ($this->orders as $order) {
            if ($order->getId() === $id) {
                return $order;
            }
        }

        return null;
    }
}