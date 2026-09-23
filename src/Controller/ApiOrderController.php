<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiOrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    #[Route('/api/orders', name: 'api_orders', methods: ['GET'], format: 'json')]
    public function orders(): JsonResponse
    {
        $orders = $this->orderService->getAllOrders();

        $data = [];

        foreach ($orders as $order) {
            $customer = $order->getCustomer();

            $data[] = [
                'id' => $order->getId(),
                'total' => $order->getTotal(),
                'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
                'customer' => [
                    'id' => $customer?->getId(),
                    'name' => $customer?->getName(),
                    'email' => $customer?->getEmail(),
                ],
            ];
        }

        return $this->json($data);
    }
}