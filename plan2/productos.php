<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ProductoModelo.php';

$usuario = usuarioActual();
$paginaActual = 'productos';

$busqueda = trim($_GET['buscar'] ?? '');
$columna = $_GET['orden'] ?? 'nombre';
$direccion = $_GET['direccion'] ?? 'asc';
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));

$resultado = ProductoModelo::listar($busqueda, $columna, $direccion, $pagina);

function enlaceOrden(string $col, string $etiqueta, string $colActual, string $dirActual, string $busqueda): string
{
    $nuevaDir = ($colActual === $col && $dirActual === 'ASC') ? 'desc' : 'asc';
    $flecha = $colActual === $col ? ($dirActual === 'ASC' ? ' ▲' : ' ▼') : '';
    $url = 'productos.php?orden=' . urlencode($col) . '&direccion=' . urlencode($nuevaDir) . '&buscar=' . urlencode($busqueda);
    return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" style="color:inherit;text-decoration:none">'
         . htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') . $flecha . '</a>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Productos</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-3)">
      <h1 class="page-title" style="margin:0">Catálogo de Productos</h1>
      <a class="button" href="producto_formulario.php">+ Nuevo producto</a>
    </div>

    <?php require __DIR__ . '/parciales/flash.php'; ?>

    <form method="get" class="search-bar">
      <input type="text" name="buscar" class="input" placeholder="Buscar por nombre o categoría..."
             value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>">
      <button type="submit" class="button secondary">Buscar</button>
      <span class="result-count"><?= $resultado['total'] ?> producto<?= $resultado['total'] === 1 ? '' : 's' ?> encontrado<?= $resultado['total'] === 1 ? '' : 's' ?></span>
    </form>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th><?= enlaceOrden('nombre', 'Nombre', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th><?= enlaceOrden('categoria', 'Categoría', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th><?= enlaceOrden('precio', 'Precio (COP)', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th><?= enlaceOrden('stock', 'Stock', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($resultado['productos'])): ?>
            <tr><td colspan="5">No se encontraron productos.</td></tr>
          <?php endif; ?>
          <?php foreach ($resultado['productos'] as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><span class="badge"><?= htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8') ?></span></td>
              <td>$<?= number_format((float) $p['precio'], 0, ',', '.') ?></td>
              <td><?= $p['stock'] < 5 ? '<span class="stock-low">' . (int) $p['stock'] . ' und</span>' : (int) $p['stock'] . ' und' ?></td>
              <?php $idProducto = (int) ($p['id'] ?? 0); ?>
              <td>
                <a class="button secondary" href="producto_formulario.php?id=<?= $idProducto ?>">Editar</a>
                <a class="button danger" href="producto_eliminar.php?id=<?= $idProducto ?>">Desactivar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($resultado['totalPaginas'] > 1): ?>
      <div class="form-actions">
        <?php for ($i = 1; $i <= $resultado['totalPaginas']; $i++): ?>
          <a class="button <?= $i === $resultado['paginaActual'] ? '' : 'secondary' ?>"
             href="productos.php?pagina=<?= $i ?>&orden=<?= urlencode($resultado['columna']) ?>&direccion=<?= urlencode($resultado['direccion']) ?>&buscar=<?= urlencode($busqueda) ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>