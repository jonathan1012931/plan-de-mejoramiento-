<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/modelos/PedidoModelo.php';

$usuario = usuarioActual();
$paginaActual = 'pedidos';

$id = (int) ($_GET['id'] ?? 0);
$lineas = PedidoModelo::obtenerDetalle($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Detalle del pedido</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <h1 class="page-title">Detalle del pedido #<?= $id ?></h1>
    <p class="page-description">Aunque un producto se haya desactivado del catálogo, aquí sigue mostrándose porque el histórico de ventas nunca se borra.</p>

    <div class="table-container">
      <table>
        <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Estado actual</th></tr></thead>
        <tbody>
          <?php foreach ($lineas as $linea): ?>
            <tr>
              <td><?= htmlspecialchars($linea['producto_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) $linea['cantidad'] ?></td>
              <td>$<?= number_format((float) $linea['precio_unitario'], 0, ',', '.') ?></td>
              <td>
                <?php if ($linea['producto_activo']): ?>
                  <span class="status ok">Activo en catálogo</span>
                <?php else: ?>
                  <span class="status warn">Desactivado (solo histórico)</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="form-actions">
      <a class="button secondary" href="pedidos.php">Volver</a>
    </div>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>