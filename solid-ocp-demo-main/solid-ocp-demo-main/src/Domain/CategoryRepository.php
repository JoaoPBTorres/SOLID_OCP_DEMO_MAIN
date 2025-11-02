<?php 

declare(strict_types=1);

namespace App\Domain;

interface CategoryRepository
{
    public function getTaxCategories(string $category): float;
}