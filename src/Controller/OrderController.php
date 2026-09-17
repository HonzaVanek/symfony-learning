<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Form\OrderType;
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
        $form = $this->createForm(OrderType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            $this->orderService->saveNewOrder($order);

            return $this->redirectToRoute('order_list');
        }

        return $this->render('order/create.html.twig', [
            'form' => $form,
        ]);
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
            throw $this->createNotFoundException(
                'Objednávka nebyla nalezena.'
            );
        }

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->orderService->saveChanges();

            return $this->redirectToRoute('order_list');
        }

        return $this->render('order/edit.html.twig', [
            'form' => $form,
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