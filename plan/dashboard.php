<?php
require __DIR__ . '/config/guardia.php';
require __DIR__ . '/config/conexion.php';

$usuario = usuarioActual();
$paginaActual = 'dashboard';

$pdo = Conexion::obtener();

$totalUsuarios = (int) $pdo->query('SELECT COUNT(*) AS total FROM usuarios')->fetch()['total'];
$usuariosActivos = (int) $pdo->query('SELECT COUNT(*) AS total FROM usuarios WHERE activo = 1')->fetch()['total'];
$usuariosBloqueados = (int) $pdo->query(
    'SELECT COUNT(*) AS total FROM usuarios WHERE bloqueado_hasta IS NOT NULL AND bloqueado_hasta > NOW()'
)->fetch()['total'];
$intentosFallidos24h = (int) $pdo->query(
    'SELECT COUNT(*) AS total FROM intentos_acceso WHERE exitoso = 0 AND creado_en >= (NOW() - INTERVAL 24 HOUR)'
)->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Dashboard responsive de Sport Zone"><title>Sport Zone | Dashboard</title>
<link rel="stylesheet" href="css/tokens.css"><link rel="stylesheet" href="css/stilos.css">
</head>
<body>

<?php require __DIR__ . '/parciales/cabecera.php'; ?>
<?php require __DIR__ . '/parciales/menu.php'; ?>

<main class="main-content">
  <h1 class="page-title">Dashboard</h1>
  <p class="page-description">Panel principal de Sport Zone. Indicadores calculados en vivo desde la base de datos.</p>

  <section aria-labelledby="resumen">
    <h2 id="resumen">Resumen</h2>
    <div class="kpi-grid">
      <article class="kpi-card">
        <h3>Usuarios Registrados</h3>
        <p>Total en el sistema</p>
        <span class="kpi-value"><?= $totalUsuarios ?></span>
      </article>
      <article class="kpi-card">
        <h3>Usuarios Activos</h3>
        <p>Cuentas habilitadas</p>
        <span class="kpi-value"><?= $usuariosActivos ?></span>
      </article>
      <article class="kpi-card">
        <h3>Cuentas Bloqueadas</h3>
        <p>Por intentos fallidos</p>
        <span class="kpi-value"><?= $usuariosBloqueados ?></span>
      </article>
      <article class="kpi-card">
        <h3>Intentos Fallidos (24h)</h3>
        <p>Accesos rechazados</p>
        <span class="kpi-value"><?= $intentosFallidos24h ?></span>
      </article>
    </div>
  </section>

  <section class="content-card" aria-labelledby="transacciones">
    <h2 id="transacciones">Últimas transacciones</h2>
    <div class="table-container"><table><thead><tr><th>ID</th><th>Cliente</th><th>Producto</th><th>Fecha</th><th>Monto</th><th>Estado</th></tr></thead><tbody>
      <tr><td>001</td><td>Carlos Pérez</td><td>Balón de fútbol</td><td>24/09/2026</td><td>$89.900</td><td><span class="status ok">Completada</span></td></tr>
      <tr><td>002</td><td>Ana Gómez</td><td>Balón de baloncesto</td><td>24/09/2026</td><td>$74.900</td><td><span class="status ok">Completada</span></td></tr>
      <tr><td>003</td><td>Luis Torres</td><td>Tenis deportivos</td><td>23/09/2026</td><td>$159.900</td><td><span class="status warn">Pendiente</span></td></tr>
      <tr><td>004</td><td>María Ruiz</td><td>Gafas de natación</td><td>23/09/2026</td><td>$49.900</td><td><span class="status off">Cancelada</span></td></tr>
    </tbody></table></div>
  </section>
</main>

<?php require __DIR__ . '/parciales/pie.php'; ?>
<script src="js/menu.js"></script>
</body>
</html>