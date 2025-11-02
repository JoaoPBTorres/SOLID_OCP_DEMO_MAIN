<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\CalculatorRepository;
use App\Domain\Product;
use App\Domain\ProductRepository;

final class ProductService
{
    public function __construct(private ProductRepository $repository, private CalculatorRepository $taxCalculator) {}

    public function create(string $name, string $category, float $basePrice): Product
    {
        $product = new Product(null, trim($name), strtolower(trim($category)), $basePrice);
        return $this->repository->add($product);
    }

    public function update(int $id, string $name, string $category, float $basePrice): void
    {
        $current = $this->repository->find($id);
        if ($current === null) { 
            return; 
        }
        $updated = new Product($id, trim($name), strtolower(trim($category)), $basePrice);
        $this->repository->update($updated);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }

    /** @return Product[] */
    public function list(): array
    {
        return $this->repository->all();
    }

    public function getById(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function finalPrice(Product $product): float
    {
        return $this->taxCalculator->calculate(
            $product->basePrice(),
            $product->category()
        );
    }
}
