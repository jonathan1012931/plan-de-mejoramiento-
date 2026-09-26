<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ClienteModelo.php';

$usuario = usuarioActual();
$paginaActual = 'clientes';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$cliente = $id ? ClienteModelo::obtener($id) : null;

if ($id && !$cliente) {
    flashEstablecer('error', 'El cliente solicitado no existe.');
    header('Location: clientes.php');
    exit;
}

$valores = $cliente ?? ['nombre' => '', 'documento' => '', 'correo' => '', 'telefono' => ''];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | <?= $id ? 'Editar' : 'Nuevo' ?> Cliente</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <h1 class="page-title"><?= $id ? 'Editar cliente' : 'Nuevo cliente' ?></h1>

    <?php require __DIR__ . '/parciales/flash.php'; ?>

    <form method="post" action="cliente_guardar.php" class="form-grid two-columns" novalidate>
      <input type="hidden" name="id" value="<?= $id ? (int) $id : '' ?>">

      <div class="form-group">
        <label for="nombre">Nombre completo</label>
        <input class="input" type="text" id="nombre" name="nombre"
               value="<?= htmlspecialchars($valores['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="form-group">
        <label for="documento">Documento (único)</label>
        <input class="input" type="text" id="documento" name="documento"
               value="<?= htmlspecialchars($valores['documento'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="form-group">
        <label for="correo">Correo electrónico (único)</label>
        <input class="input" type="email" id="correo" name="correo"
               value="<?= htmlspecialchars($valores['correo'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input class="input" type="text" id="telefono" name="telefono"
               value="<?= htmlspecialchars($valores['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
      </div>

      <div class="form-actions">
        <button type="submit" class="button">Guardar</button>
        <a class="button secondary" href="clientes.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>