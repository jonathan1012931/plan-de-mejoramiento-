<?php

require __DIR__ . '/config/conexion.php';

$usuariosSemilla = [
    ['nombre' => 'Admin Sport Zone', 'correo' => 'admin@sportzone.test', 'clave' => 'Admin#2026', 'rol' => 'administrador'],
    ['nombre' => 'Vendedor Sport Zone', 'correo' => 'vendedor@sportzone.test', 'clave' => 'Vende#2026', 'rol' => 'vendedor'],
    ['nombre' => 'Consultor Sport Zone', 'correo' => 'consultor@sportzone.test', 'clave' => 'Consulta#2026', 'rol' => 'consultor'],
];

$pdo = Conexion::obtener();

$verificar = $pdo->prepare('SELECT id FROM usuarios WHERE correo = :correo');
$insertar = $pdo->prepare(
    'INSERT INTO usuarios (nombre, correo, clave_hash, rol, activo)
     VALUES (:nombre, :correo, :clave_hash, :rol, 1)'
);

foreach ($usuariosSemilla as $u) {
    $verificar->execute(['correo' => $u['correo']]);

    if ($verificar->fetch()) {
        echo "Ya existe: {$u['correo']}<br>";
        continue;
    }

    $claveHash = password_hash($u['clave'], PASSWORD_BCRYPT);

    $insertar->execute([
        'nombre' => $u['nombre'],
        'correo' => $u['correo'],
        'clave_hash' => $claveHash,
        'rol' => $u['rol'],
    ]);

    echo "Creado: {$u['correo']} ({$u['rol']})<br>";
}

echo '<hr>Credenciales de prueba:<br>';
foreach ($usuariosSemilla as $u) {
    echo htmlspecialchars($u['correo']) . ' → ' . htmlspecialchars($u['clave']) . '<br>';
}