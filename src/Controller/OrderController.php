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

    #[Route('/orders/{id}/edit', name: 'order_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $order = $this->orderService->getOrder($id);

        if ($order === null) {
            throw $this->createNotFoundException('Objednávka nebyla nalezena.');
        }

        if ($request->isMethod('POST')) {
            $customerName = $request->request->getString('customerName');
            $total = $request->request->getInt('total');

            $this->orderService->updateOrder(
                $order,
                $customerName,
                $total,
            );

            return $this->redirectToRoute('order_list');
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/orders/{id}/delete', name: 'order_delete', methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        $order = $this->orderService->getOrder($id);

        if ($order === null) {
            throw $this->createNotFoundException('Objednávka nebyla nalezena.');
        }


        $token = $request->request->getString('_token');
        if (!$this->isCsrfTokenValid('delete-order-' . $id, $token)) {
            throw $this->createAccessDeniedException(
                'Neplatný CSRF token.'
            );
        }


        $this->orderService->deleteOrder($order);

        return $this->redirectToRoute('order_list');
    }
}