<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ProductoModelo.php';

$usuario = usuarioActual();
$paginaActual = 'productos';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$producto = ProductoModelo::obtener($id);

if (!$producto) {
    flashEstablecer('error', 'El producto solicitado no existe.');
    header('Location: productos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ProductoModelo::desactivar($id);
    flashEstablecer('exito', 'Producto desactivado. Ya no aparece en el catálogo, pero se conserva en los pedidos históricos.');
    header('Location: productos.php');
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
      ¿Seguro que deseas desactivar <strong><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8') ?></strong>?
      No se elimina de la base de datos; sigue visible en los pedidos ya realizados, solo deja de aparecer en el catálogo activo.
    </p>
    <form method="post" action="producto_eliminar.php">
      <input type="hidden" name="id" value="<?= (int) $producto['id'] ?>">
      <div class="form-actions">
        <button type="submit" class="button danger">Sí, desactivar</button>
        <a class="button secondary" href="productos.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>