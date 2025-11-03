<?php 

declare(strict_types=1);

namespace App\Domain;

use App\Domain\CategoryRepository;

final class Category implements CategoryRepository
{
    private array $taxRates;

    public function __construct(array $taxRates)
    {
        $this->taxRates = $taxRates;
    }

    public function getTaxCategories(string $category): float
    {
        return $this->taxRates[$category] ?? 0;
    }

}

