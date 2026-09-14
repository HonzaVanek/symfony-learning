<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    #[Route('/order/{id}', name: 'app_order')]
    public function detail(int $id): Response
    {
        $total = $this->orderService->getOrderTotal($id);

        if ($total === null) {
            return new Response('Objednávka neexistuje.', 404);
        }

        return new Response("Cena objednávky je: $total");
    }
}