<?php 
declare(strict_types=1);
use App\Application\ProductService;
use App\Domain\Calculator;
use App\Domain\Category;
use App\Infra\FileProductRepository;

$categories = [
    'livro' => 0.0,
    'eletronico' => 0.15,
    'alimento' => 0.08,
];

$categoryRepository = new Category($categories);
$taxCalculator = new Calculator($categoryRepository);
$file = __DIR__ . '/../storage/products.txt';
$productRepository = new FileProductRepository($file);

return new ProductService($productRepository, $taxCalculator);