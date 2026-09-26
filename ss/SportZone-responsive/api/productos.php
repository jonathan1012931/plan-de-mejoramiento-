```php
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/conexion.php';

try {

    $pdo = Conexion::obtener();

    $sql = "
        SELECT
            id_producto AS id,
            nombre,
            categoria,
            precio,
            stock
        FROM productos
        WHERE activo = 1
        ORDER BY id_producto ASC
    ";

    $stmt = $pdo->query($sql);

    $productos = $stmt->fetchAll();

    echo json_encode(
        $productos,
        JSON_UNESCAPED_UNICODE
    );

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}