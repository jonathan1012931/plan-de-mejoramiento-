<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ClienteModelo.php';

$usuario = usuarioActual();
$paginaActual = 'clientes';

$busqueda = trim($_GET['buscar'] ?? '');
$columna = $_GET['orden'] ?? 'nombre';
$direccion = $_GET['direccion'] ?? 'asc';
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));

$resultado = ClienteModelo::listar($busqueda, $columna, $direccion, $pagina);

function enlaceOrdenCliente(string $col, string $etiqueta, string $colActual, string $dirActual, string $busqueda): string
{
    $nuevaDir = ($colActual === $col && $dirActual === 'ASC') ? 'desc' : 'asc';
    $flecha = $colActual === $col ? ($dirActual === 'ASC' ? ' ▲' : ' ▼') : '';
    $url = 'clientes.php?orden=' . urlencode($col) . '&direccion=' . urlencode($nuevaDir) . '&buscar=' . urlencode($busqueda);
    return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" style="color:inherit;text-decoration:none">'
         . htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8') . $flecha . '</a>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Clientes</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-3)">
      <h1 class="page-title" style="margin:0">Clientes</h1>
      <a class="button" href="cliente_formulario.php">+ Nuevo cliente</a>
    </div>

    <?php require __DIR__ . '/parciales/flash.php'; ?>

    <form method="get" class="search-bar">
      <input type="text" name="buscar" class="input" placeholder="Buscar por nombre, documento o correo..."
             value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>">
      <button type="submit" class="button secondary">Buscar</button>
      <span class="result-count"><?= $resultado['total'] ?> cliente<?= $resultado['total'] === 1 ? '' : 's' ?> encontrado<?= $resultado['total'] === 1 ? '' : 's' ?></span>
    </form>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th><?= enlaceOrdenCliente('nombre', 'Nombre', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th><?= enlaceOrdenCliente('documento', 'Documento', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th><?= enlaceOrdenCliente('correo', 'Correo', $resultado['columna'], $resultado['direccion'], $busqueda) ?></th>
            <th>Teléfono</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($resultado['clientes'])): ?>
            <tr><td colspan="5">No se encontraron clientes.</td></tr>
          <?php endif; ?>
          <?php foreach ($resultado['clientes'] as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($c['documento'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($c['correo'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($c['telefono'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <a class="button secondary" href="cliente_formulario.php?id=<?= (int) $c['id'] ?>">Editar</a>
                <a class="button danger" href="cliente_eliminar.php?id=<?= (int) $c['id'] ?>">Desactivar</a>
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
             href="clientes.php?pagina=<?= $i ?>&orden=<?= urlencode($resultado['columna']) ?>&direccion=<?= urlencode($resultado['direccion']) ?>&buscar=<?= urlencode($busqueda) ?>">
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