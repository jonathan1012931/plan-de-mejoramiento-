<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';

$usuario = usuarioActual();
$paginaActual = 'pedidos';

$pdo = Conexion::obtener();
$clientes = $pdo->query('SELECT id, nombre, documento FROM clientes WHERE activo = 1 ORDER BY nombre')->fetchAll();
$productos = $pdo->query('SELECT id_producto AS id, nombre, precio, stock FROM productos WHERE activo = 1 ORDER BY nombre')->fetchAll();

const NUMERO_LINEAS = 5;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Nuevo pedido</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <h1 class="page-title">Nuevo pedido</h1>
    <p class="page-description">Selecciona el cliente y hasta <?= NUMERO_LINEAS ?> productos con su cantidad. Las líneas vacías se ignoran.</p>

    <?php require __DIR__ . '/parciales/flash.php'; ?>

    <form method="post" action="pedido_guardar.php" novalidate>
      <div class="form-group">
        <label for="cliente_id">Cliente</label>
        <select class="select" id="cliente_id" name="cliente_id" required>
          <option value="">Selecciona un cliente</option>
          <?php foreach ($clientes as $c): ?>
            <option value="<?= (int) $c['id'] ?>">
              <?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($c['documento'], ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="table-container" style="margin-top:var(--space-4)">
        <table>
          <thead><tr><th>Producto</th><th>Cantidad</th></tr></thead>
          <tbody>
            <?php for ($i = 0; $i < NUMERO_LINEAS; $i++): ?>
              <tr>
                <td>
                  <select class="select" name="producto_id[]">
                    <option value="">— Sin producto —</option>
                    <?php foreach ($productos as $p): ?>
                      <option value="<?= (int) $p['id'] ?>">
                        <?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?> (stock: <?= (int) $p['stock'] ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input class="input" type="number" name="cantidad[]" min="1" placeholder="0"></td>
              </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>

      <div class="form-actions">
        <button type="submit" class="button">Registrar pedido</button>
        <a class="button secondary" href="pedidos.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>