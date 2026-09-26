<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ClienteModelo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: clientes.php');
    exit;
}

$id = trim($_POST['id'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$documento = trim($_POST['documento'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

$errores = [];
$idExcluir = $id !== '' ? (int) $id : null;

if (mb_strlen($nombre) < 3) {
    $errores[] = 'El nombre debe tener mínimo 3 caracteres.';
}
if ($documento === '') {
    $errores[] = 'El documento es obligatorio.';
} elseif (ClienteModelo::documentoExiste($documento, $idExcluir)) {
    $errores[] = 'Ese documento ya está registrado con otro cliente.';
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El correo no es válido.';
} elseif (ClienteModelo::correoExiste($correo, $idExcluir)) {
    $errores[] = 'Ese correo ya está registrado con otro cliente.';
}

if (!empty($errores)) {
    flashEstablecer('error', implode(' ', $errores));
    header('Location: ' . ($id !== '' ? "cliente_formulario.php?id=$id" : 'cliente_formulario.php'));
    exit;
}

$datos = [
    'nombre' => $nombre,
    'documento' => $documento,
    'correo' => $correo,
    'telefono' => $telefono !== '' ? $telefono : null,
];

if ($id !== '') {
    ClienteModelo::actualizar((int) $id, $datos);
    flashEstablecer('exito', 'Cliente actualizado correctamente.');
} else {
    ClienteModelo::crear($datos);
    flashEstablecer('exito', 'Cliente creado correctamente.');
}

header('Location: clientes.php');
exit;