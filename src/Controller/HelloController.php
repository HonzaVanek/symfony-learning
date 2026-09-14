<?php

namespace App\Controller;

use App\Service\GreetingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    public function __construct(
        private readonly GreetingService $brambora,
    ) {
    }

    #[Route('/hello/{name}', name: 'app_hello')]
    public function hello(string $name): Response
    {
        $message = $this->brambora->getGreeting($name);

        return $this->render('hello/index.html.twig', [
            'name' => $name,
            'message' => $message,
        ]);
    }
}