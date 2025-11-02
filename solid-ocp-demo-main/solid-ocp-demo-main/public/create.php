<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$service = require __DIR__ . '/../config/categories.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = (string)($_POST['name'] ?? '');
    $category  = (string)($_POST['category'] ?? '');
    $price= (float)($_POST['basePrice'] ?? 0);

    $service->create($name, $category, $price);
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head><meta charset="utf-8" /><title>Criar</title></head>
<body>
  <h1>Criar Produto</h1>
  <form method="post">
    <p><label>Nome: <input name="name" required></label></p>
    <p><label>Categoria (livro \ eletronico \ alimento): <input name="category" required></label></p>
    <p><label>Preço base: <input type="number" step="0.01" name="basePrice" required></label></p>
    <p><button type="submit">Salvar</button> <a href="index.php">Voltar</a></p>
  </form>
</body>
</html>
