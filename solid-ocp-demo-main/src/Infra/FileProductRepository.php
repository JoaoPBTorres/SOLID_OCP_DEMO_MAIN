<?php

declare(strict_types=1);

namespace App\Infra;

use App\Domain\Product;
use App\Domain\ProductRepository;
use RuntimeException;

final class FileProductRepository implements ProductRepository
{
    public function __construct(private string $filePath)
    {
        $directory = \dirname($this->filePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }

    /** @return Product[] */
    public function all(): array
    {
        $lines = @file($this->filePath, FILE_IGNORE_NEW_LINES) ?: [];
        $items = [];

        foreach ($lines as $line) {
            if (trim($line) === '') { 
                continue; 
            }

            $row = json_decode($line, true);

            if (!is_array($row)) { 
                continue; 
            }

            $items[] = Product::fromArray([
                'id'        => $row['id'] ?? null,
                'name'      => $row['name'] ?? '',
                'category'  => $row['category'] ?? '',
                'basePrice' => (float)($row['basePrice'] ?? 0.0),
            ]);
        }
        return $items;
    }

    public function find(int $id): ?Product
    {
        foreach ($this->all() as $product) {
            if ($product->id() === $id) { 
                return $product; 
            }
        }
        return null;
    }

    public function add(Product $product): Product
    {
        $allProducts = $this->all();
        $newId = $this->nextId($allProducts);
        $withId = $product->withId($newId);
        $this->append($withId);
        return $withId;
    }

    public function update(Product $product): void
    {
        if ($product->id() === null) {
            throw new RuntimeException('Cannot update product without ID');
        }

        $allProducts = $this->all();
        $out = [];

        foreach ($allProducts as $item) {
            if ($item->id() === $product->id()) {
                $out[] = $product;
            } else {
                $out[] = $item;
            }
        }

        $this->rewrite($out);
    }

    public function delete(int $id): void
    {
        $allProducts = $this->all();
        $out = array_filter($allProducts, fn(Product $product) => $product->id() !== $id);
        $this->rewrite(array_values($out));
    }

    /** @param Product[] $items */
    private function rewrite(array $items): void
    {
        $fileHandle = fopen($this->filePath, 'w');

        if ($fileHandle === false) { 
            throw new RuntimeException('Cannot open file'); 
        }

        foreach ($items as $product) {
            fwrite($fileHandle, json_encode($product->toArray(), JSON_UNESCAPED_UNICODE) . PHP_EOL);
        }
        
        fclose($fileHandle);
    }

    private function append(Product $product): void
    {
        file_put_contents(
            $this->filePath,
            json_encode($product->toArray(), JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );
    }

    /** @param Product[] $allProducts */
    private function nextId(array $allProducts): int
    {
        $max = 0;
        foreach ($allProducts as $product) {
            $max = max($max, (int)$product->id());
        }
        return $max + 1;
    }
}
