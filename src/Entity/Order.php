<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Jméno zákazníka nesmí být prázdné.')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Jméno musí mít alespoň {{ limit }} znaky.',
        maxMessage: 'Jméno může mít maximálně {{ limit }} znaků.',
    )]
    private string $customerName;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: 'Cena objednávky nesmí být záporná.')]
    private int $total;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Customer $customer = null;

    public function __construct(string $customerName, int $total)
    {
        $this->customerName = $customerName;
        $this->total = $total;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}