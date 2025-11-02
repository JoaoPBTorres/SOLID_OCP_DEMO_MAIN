<?php

declare(strict_types=1);

namespace App\Domain;

use InvalidArgumentException;

final class Calculator implements CalculatorRepository
{

    public function __construct(private CategoryRepository $categoryRepository){}

    public function calculate(float $basePrice, string $category): float
    {
        $tax = $this->categoryRepository->getTaxCategories($category);
        return $basePrice + ($basePrice * $tax);
    }
}