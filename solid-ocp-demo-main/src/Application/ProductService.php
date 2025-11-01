<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\CalculatorRepository;
use App\Domain\Product;
use App\Domain\ProductRepository;

final class ProductService
{
    public function __construct(private ProductRepository $repo, private CalculatorRepository $taxCalculator) {}

    public function create(string $name, string $category, float $basePrice): Product
    {
        $product = new Product(null, trim($name), strtolower(trim($category)), $basePrice);
        return $this->repo->add($product);
    }

    public function update(int $id, string $name, string $category, float $basePrice): void
    {
        $current = $this->repo->find($id);
        if ($current === null) { 
            return; 
        }
        $updated = new Product($id, trim($name), strtolower(trim($category)), $basePrice);
        $this->repo->update($updated);
    }

    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }

    /** @return Product[] */
    public function list(): array
    {
        return $this->repo->all();
    }

    public function getById(int $id): ?Product
    {
        return $this->repo->find($id);
    }

    public function finalPrice(Product $product): float
    {
        return $this->taxCalculator->calculate(
            $product->basePrice(),
            $product->category()
        );
    }
}
