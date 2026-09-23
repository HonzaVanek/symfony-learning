<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

# tady importy pro POST:
use App\Entity\Order;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

# pro delete:
use Symfony\Component\HttpFoundation\Response;


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

    #[Route('/api/orders', name: 'api_orders_create', methods: ['POST'], format: 'json')]
    public function create(
        Request $request,
        CustomerRepository $customerRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $data = $request->toArray();

        $customerId = (int) ($data['customerId'] ?? 0);
        $total = (int) ($data['total'] ?? 0);

        $customer = $customerRepository->find($customerId);

        if ($customer === null) {
            return $this->json([
                'error' => 'Customer not found',
            ], 404);
        }

        $order = new Order($customer, $total);

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json([
            'id' => $order->getId(),
            'total' => $order->getTotal(),
            'customer' => [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
            ],
        ], 201);
    }

    #[Route('/api/orders/{id}', name: 'api_orders_delete', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): Response {
        $order = $this->orderService->getOrder($id);

        if ($order === null) {
            return $this->json([
                'error' => 'Objednávka nebyla nalezena.',
            ], 404);
        }

        $this->orderService->deleteOrder($order);

        return new Response(status: 204);
    }

}