<?php
require __DIR__ . '/config/guardia.php';
$usuario = usuarioActual();
$paginaActual = 'productos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Catálogo y gestión de productos de Sport Zone">
<title>Sport Zone | Productos</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">

  <section id="analisis" aria-labelledby="titulo-analisis">
    <h1 id="titulo-analisis" class="page-title">Análisis Estadístico de Productos</h1>
    <p class="page-description">Resultados calculados mediante métodos funcionales de arreglos (sin bucles <code>for</code>) y formateados en COP.</p>

    <div class="kpi-grid">
      <article class="kpi-card"><h3>Producto Más Caro</h3><p id="res-mas-caro">-</p><span id="res-precio-caro" class="kpi-value">-</span></article>
      <article class="kpi-card"><h3>Promedio de Precios</h3><p>Valor medio del catálogo</p><span id="res-promedio" class="kpi-value">-</span></article>
      <article class="kpi-card"><h3>Stock Crítico (&lt; 5 und)</h3><p>Productos con alerta de stock</p><span id="res-stock-critico" class="kpi-value">-</span></article>
      <article class="kpi-card"><h3>Catálogo General</h3><p>Total productos registrados</p><span id="res-total-items" class="kpi-value">-</span></article>
    </div>

    <div class="two-col-grid">
      <div class="content-card"><h2>Total de Unidades por Categoría</h2><div id="tabla-categorias"></div></div>
      <div class="content-card"><h2>Listado de Productos con Stock &lt; 5</h2><div id="tabla-stock-critico"></div></div>
    </div>
  </section>

  <section id="productos" class="content-card" aria-labelledby="titulo-productos">
    <h2 id="titulo-productos">Catálogo de Productos (15 Ítems)</h2>
    <p class="page-description">Estructura simulada de base de datos relacional.</p>

    <div class="search-bar">
      <input type="text" id="buscador-productos" class="input" placeholder="Buscar por nombre o categoría...">
      <span id="contador-resultados" class="result-count">15 productos encontrados</span>
    </div>

    <div class="table-container">
      <table>
        <thead><tr><th>ID</th><th>Nombre del Producto</th><th>Categoría</th><th>Precio (COP)</th><th>Stock</th></tr></thead>
        <tbody id="tbody-productos"></tbody>
      </table>
    </div>

    <h2>Agregar Producto</h2>
    <form id="form-producto" class="form-grid two-columns" novalidate>
      <div class="form-group">
        <label for="input-nombre">Nombre del producto</label>
        <input type="text" id="input-nombre" class="input">
        <p id="error-nombre" class="field-error"></p>
      </div>
      <div class="form-group">
        <label for="input-categoria">Categoría</label>
        <select id="input-categoria" class="select">
          <option value="">Selecciona una categoría</option>
          <option>Fútbol</option><option>Running</option><option>Textil</option><option>Gym</option>
          <option>Yoga</option><option>Tenis</option><option>Ciclismo</option><option>Accesorios</option>
          <option>Salud</option><option>Suplementos</option><option>Natación</option>
        </select>
        <p id="error-categoria" class="field-error"></p>
      </div>
      <div class="form-group">
        <label for="input-precio">Precio (COP)</label>
        <input type="number" id="input-precio" class="input">
        <p id="error-precio" class="field-error"></p>
      </div>
      <div class="form-group">
        <label for="input-stock">Stock</label>
        <input type="number" id="input-stock" class="input">
        <p id="error-stock" class="field-error"></p>
      </div>
      <div class="form-actions">
        <button type="submit" class="button">Guardar Producto</button>
      </div>
    </form>
  </section>

  <section id="pedidos" class="content-card" aria-labelledby="titulo-pedidos">
    <h2 id="titulo-pedidos">Gestión de Pedidos (8 Pedidos)</h2>
    <p class="page-description">Historial reciente de transacciones y compras.</p>
    <div class="table-container">
      <table>
        <thead><tr><th>ID Pedido</th><th>Cliente</th><th>ID Producto</th><th>Cantidad</th><th>Fecha</th></tr></thead>
        <tbody id="tbody-pedidos"></tbody>
      </table>
    </div>
  </section>

</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>

<script src="js/menu.js"></script>
<script type="module" src="js/productos.js"></script>
</body>
</html>