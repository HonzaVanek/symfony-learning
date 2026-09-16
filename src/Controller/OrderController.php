<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    #[Route('/orders/create', name: 'order_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $total = (int) $request->request->get('total');
            $customerName = $request->request->get('customerName');

            $order = $this->orderService->createOrder(
                customerName: $customerName,
                total: $total,
            );

            return new Response(
                'Objednávka vytvořena. ID: ' . $order->getId()
            );
        }

        return $this->render('order/create.html.twig');
    }

    #[Route('/orders', name: 'order_list', methods: ['GET'])]
    public function list(): Response
    {
        $orders = $this->orderService->getAllOrders();

        return $this->render('order/list.html.twig', [
            'orders' => $orders,
        ]);
    }
}