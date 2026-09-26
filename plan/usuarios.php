<?php
require __DIR__ . '/config/guardia.php';
requerirRol(['administrador']);
$usuario = usuarioActual();
require __DIR__ . '/config/conexion.php';

$pdo = Conexion::obtener();
$usuarios = $pdo->query(
    'SELECT id, nombre, correo, rol, activo, bloqueado_hasta, creado_en FROM usuarios ORDER BY id'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Usuarios</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>
<header class="site-header">
  <a href="dashboard.php" aria-label="Ir al inicio"><img class="site-logo" src="assets/img/logo.png" alt="Logo Sport Zone"></a>
  <div class="user-info">
    <span><?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
    <span class="user-role"><?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8') ?></span>
    <a class="logout-link" href="salir.php">Salir</a>
  </div>
</header>
<main class="main-content">
  <h1 class="page-title">Usuarios registrados</h1>
  <p class="page-description">El nombre se muestra escapado con <code>htmlspecialchars()</code>: si alguien registra un <code>&lt;script&gt;</code> en el nombre, aquí se ve como texto, no se ejecuta.</p>

  <div class="table-container">
    <table>
      <thead>
        <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Creado</th></tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $u): ?>
          <tr>
            <td>#<?= (int) $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($u['correo'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><span class="badge"><?= htmlspecialchars($u['rol'], ENT_QUOTES, 'UTF-8') ?></span></td>
            <td>
              <?php if ($u['bloqueado_hasta'] && strtotime($u['bloqueado_hasta']) > time()): ?>
                <span class="status warn">Bloqueado</span>
              <?php elseif (!$u['activo']): ?>
                <span class="status off">Inactivo</span>
              <?php else: ?>
                <span class="status ok">Activo</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($u['creado_en'], ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>