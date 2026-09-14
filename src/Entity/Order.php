<?php

namespace App\Entity;

class Order
{
    public function __construct(
        private int $id,
        private int $total,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}