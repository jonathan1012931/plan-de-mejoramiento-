<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';
require __DIR__ . '/config/flash.php';
require __DIR__ . '/modelos/ProductoModelo.php';

$usuario = usuarioActual();
$paginaActual = 'productos';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$producto = $id ? ProductoModelo::obtener($id) : null;

if ($id && !$producto) {
    flashEstablecer('error', 'El producto solicitado no existe.');
    header('Location: productos.php');
    exit;
}

$valores = $producto ?? ['nombre' => '', 'categoria' => '', 'precio' => '', 'stock' => ''];
$categorias = ['Fútbol','Running','Textil','Gym','Yoga','Tenis','Ciclismo','Accesorios','Salud','Suplementos','Natación'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | <?= $id ? 'Editar' : 'Nuevo' ?> Producto</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <div class="content-card">
    <h1 class="page-title"><?= $id ? 'Editar producto' : 'Nuevo producto' ?></h1>

    <?php require __DIR__ . '/parciales/flash.php'; ?>

    <form method="post" action="producto_guardar.php" class="form-grid two-columns" novalidate>
      <input type="hidden" name="id" value="<?= $id ? (int) $id : '' ?>">

      <div class="form-group">
        <label for="nombre">Nombre del producto</label>
        <input class="input" type="text" id="nombre" name="nombre"
               value="<?= htmlspecialchars($valores['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="form-group">
        <label for="categoria">Categoría</label>
        <select class="select" id="categoria" name="categoria" required>
          <option value="">Selecciona una categoría</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>" <?= $valores['categoria'] === $cat ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="precio">Precio (COP)</label>
        <input class="input" type="number" id="precio" name="precio" step="1"
               value="<?= htmlspecialchars((string) $valores['precio'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="form-group">
        <label for="stock">Stock</label>
        <input class="input" type="number" id="stock" name="stock" step="1"
               value="<?= htmlspecialchars((string) $valores['stock'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="form-actions">
        <button type="submit" class="button">Guardar</button>
        <a class="button secondary" href="productos.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>