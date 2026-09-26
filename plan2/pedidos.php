<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/PedidoModelo.php';

$usuario = usuarioActual();
$paginaActual = 'pedidos';
$pedidos = PedidoModelo::listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Pedidos</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-3)">
      <h1 class="page-title" style="margin:0">Pedidos</h1>
      <a class="button" href="pedido_formulario.php">+ Nuevo pedido</a>
    </div>

    <?php require __DIR__ . '/parciales/flash.php'; ?>

    <div class="table-container">
      <table>
        <thead><tr><th>ID</th><th>Cliente</th><th>Total</th><th>Fecha</th><th>Detalle</th></tr></thead>
        <tbody>
          <?php if (empty($pedidos)): ?>
            <tr><td colspan="5">Todavía no hay pedidos registrados.</td></tr>
          <?php endif; ?>
          <?php foreach ($pedidos as $p): ?>
            <tr>
              <td>#<?= (int) $p['id'] ?></td>
              <td><?= htmlspecialchars($p['cliente_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>$<?= number_format((float) $p['total'], 0, ',', '.') ?></td>
              <td><?= htmlspecialchars($p['creado_en'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><a class="button secondary" href="pedido_detalle.php?id=<?= (int) $p['id'] ?>">Ver</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>