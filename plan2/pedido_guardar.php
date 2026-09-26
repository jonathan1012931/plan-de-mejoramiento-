<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ProductoModelo.php';
require __DIR__ . '/modelos/PedidoModelo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pedidos.php');
    exit;
}

$clienteId = (int) ($_POST['cliente_id'] ?? 0);
$productosIds = $_POST['producto_id'] ?? [];
$cantidades = $_POST['cantidad'] ?? [];

if ($clienteId <= 0) {
    flashEstablecer('error', 'Debes seleccionar un cliente.');
    header('Location: pedido_formulario.php');
    exit;
}

$lineas = [];
foreach ($productosIds as $indice => $productoId) {
    $productoId = (int) $productoId;
    $cantidad = (int) ($cantidades[$indice] ?? 0);
    if ($productoId > 0 && $cantidad > 0) {
        $lineas[] = ['producto_id' => $productoId, 'cantidad' => $cantidad];
    }
}

if (empty($lineas)) {
    flashEstablecer('error', 'Agrega al menos un producto con cantidad mayor a cero.');
    header('Location: pedido_formulario.php');
    exit;
}

try {
    $pedidoId = PedidoModelo::crearConDetalle($clienteId, $lineas);
    flashEstablecer('exito', "Pedido #$pedidoId registrado correctamente. El stock ya fue descontado.");
    header('Location: pedidos.php');
    exit;
} catch (Exception $e) {
    flashEstablecer('error', 'No se pudo registrar el pedido: ' . $e->getMessage());
    header('Location: pedido_formulario.php');
    exit;
}