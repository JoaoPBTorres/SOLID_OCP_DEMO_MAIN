<?php

declare(strict_types=1);

namespace App\Domain;

interface CalculatorRepository
{
    public function calculate(float $basePrice, string $category): float;
}