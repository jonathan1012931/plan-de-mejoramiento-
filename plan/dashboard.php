<?php
require __DIR__ . '/config/guardia.php';
$usuario = usuarioActual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Dashboard responsive de Sport Zone"><title>Sport Zone | Dashboard</title>
<link rel="stylesheet" href="css/tokens.css"><link rel="stylesheet" href="css/stilos.css">
</head>
<body>
<header class="site-header">
  <a href="dashboard.php" aria-label="Ir al inicio"><img class="site-logo" src="assets/img/logo.png" alt="Logo Sport Zone"></a>
  <div class="user-info">
    <span><?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
    <span class="user-role"><?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8') ?></span>
    <a class="logout-link" href="salir.php">Salir</a>
  </div>
  <button class="menu-button" type="button" aria-label="Abrir menú">☰</button>
</header>
<aside class="sidebar abierto">
  <h2 class="sidebar-title">Navegación</h2>
  <nav aria-label="Navegación principal">
    <a class="active" href="dashboard.php">🏠 Dashboard</a>
    <a href="productos.php">📦 Productos</a>
    <a href="componentes.html">🎨 Componentes</a>
    <a href="salir.php">🚪 Cerrar sesión</a>
  </nav>
  <p class="sidebar-note"><strong>CSS por ahora:</strong> la clase <code>.abierto</code> está aplicada manualmente.</p>
</aside>
<main class="main-content">
  <h1 class="page-title">Dashboard</h1><p class="page-description">Panel principal de Sport Zone. Diseño desarrollado con enfoque <strong>Mobile First</strong>.</p>
  <section aria-labelledby="resumen"><h2 id="resumen">Resumen</h2><div class="kpi-grid">
    <article class="kpi-card"><h3>Productos</h3><p>Registrados</p><span class="kpi-value">24</span></article>
    <article class="kpi-card"><h3>Deportes</h3><p>Disponibles</p><span class="kpi-value">8</span></article>
    <article class="kpi-card"><h3>Usuarios</h3><p>Activos</p><span class="kpi-value">35</span></article>
    <article class="kpi-card"><h3>Ventas</h3><p>Este mes</p><span class="kpi-value">$4.5 M</span></article>
  </div></section>
  <section class="content-card" aria-labelledby="transacciones"><h2 id="transacciones">Últimas transacciones</h2>
    <div class="table-container"><table><thead><tr><th>ID</th><th>Cliente</th><th>Producto</th><th>Fecha</th><th>Monto</th><th>Estado</th></tr></thead><tbody>
      <tr><td>001</td><td>Carlos Pérez</td><td>Balón de fútbol</td><td>24/09/2026</td><td>$89.900</td><td><span class="status ok">Completada</span></td></tr>
      <tr><td>002</td><td>Ana Gómez</td><td>Balón de baloncesto</td><td>24/09/2026</td><td>$74.900</td><td><span class="status ok">Completada</span></td></tr>
      <tr><td>003</td><td>Luis Torres</td><td>Tenis deportivos</td><td>23/09/2026</td><td>$159.900</td><td><span class="status warn">Pendiente</span></td></tr>
      <tr><td>004</td><td>María Ruiz</td><td>Gafas de natación</td><td>23/09/2026</td><td>$49.900</td><td><span class="status off">Cancelada</span></td></tr>
    </tbody></table></div>
  </section>
</main>
<footer class="site-footer">Sport Zone © 2026 · Prototipo responsive</footer>
</body></html>