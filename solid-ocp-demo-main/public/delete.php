<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$service = require __DIR__ . '/../config/categories.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Método não permitido';
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $service->delete($id);
}
header('Location: index.php');
