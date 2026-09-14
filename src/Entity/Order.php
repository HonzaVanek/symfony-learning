<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $total;

    public function __construct(int $total)
    {
        $this->total = $total;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}