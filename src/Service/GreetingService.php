<?php

namespace App\Service;

class GreetingService
{
    public function getGreeting(string $name): string
    {
        return "Ahoj " . $name . "e ze služby!";
    }
}
