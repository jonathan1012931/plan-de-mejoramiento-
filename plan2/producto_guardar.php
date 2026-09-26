<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ProductoModelo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: productos.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$precioTexto = trim($_POST['precio'] ?? '');
$stockTexto = trim($_POST['stock'] ?? '');

$errores = [];

if (mb_strlen($nombre) < 3) {
    $errores[] = 'El nombre es obligatorio y debe tener mínimo 3 caracteres.';
}
if ($categoria === '') {
    $errores[] = 'Debes seleccionar una categoría.';
}
if ($precioTexto === '' || !is_numeric($precioTexto)) {
    $errores[] = 'El precio debe ser un valor numérico.';
} elseif ((float) $precioTexto <= 0) {
    $errores[] = 'El precio debe ser mayor que cero.';
}
if ($stockTexto === '' || !is_numeric($stockTexto)) {
    $errores[] = 'El stock debe ser un valor numérico.';
} elseif ((int) $stockTexto != $stockTexto || (int) $stockTexto < 0) {
    $errores[] = 'El stock debe ser un número entero igual o mayor a cero.';
}

if (!empty($errores)) {
    flashEstablecer('error', implode(' ', $errores));
    header('Location: ' . ($id !== '' ? "producto_formulario.php?id=$id" : 'producto_formulario.php'));
    exit;
}

$datos = [
    'nombre' => $nombre,
    'categoria' => $categoria,
    'precio' => (float) $precioTexto,
    'stock' => (int) $stockTexto,
];

if ($id !== '') {
    ProductoModelo::actualizar((int) $id, $datos);
    flashEstablecer('exito', 'Producto actualizado correctamente.');
} else {
    ProductoModelo::crear($datos);
    flashEstablecer('exito', 'Producto creado correctamente.');
}

header('Location: productos.php');
exit;