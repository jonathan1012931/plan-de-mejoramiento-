<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ClienteModelo.php';

$usuario = usuarioActual();
$paginaActual = 'clientes';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$cliente = ClienteModelo::obtener($id);

if (!$cliente) {
    flashEstablecer('error', 'El cliente solicitado no existe.');
    header('Location: clientes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ClienteModelo::desactivar($id);
    flashEstablecer('exito', 'Cliente desactivado correctamente.');
    header('Location: clientes.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Confirmar desactivación</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <h1 class="page-title">Confirmar desactivación</h1>
    <p class="page-description">
      ¿Seguro que deseas desactivar a <strong><?= htmlspecialchars($cliente['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>?
      Sus pedidos anteriores se conservan intactos.
    </p>
    <form method="post" action="cliente_eliminar.php">
      <input type="hidden" name="id" value="<?= (int) $cliente['id'] ?>">
      <div class="form-actions">
        <button type="submit" class="button danger">Sí, desactivar</button>
        <a class="button secondary" href="clientes.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>