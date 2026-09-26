<?php
require __DIR__ . '/config/sesion.php';
require __DIR__ . '/config/conexion.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$mensaje = '';
$tipoMensaje = 'error';
$maxIntentos = 5;
$ventanaMinutos = 15;

if (isset($_GET['registrado'])) {
    $mensaje = 'Registro exitoso. Ahora puedes iniciar sesión.';
    $tipoMensaje = 'exito';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tokenEnviado = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $tokenEnviado)) {
        $tipoMensaje = 'error';
        $mensaje = 'Solicitud inválida, intenta de nuevo.';
    } else {

        $correo = trim($_POST['email'] ?? '');
        $clave = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $pdo = Conexion::obtener();

        $buscar = $pdo->prepare('SELECT * FROM usuarios WHERE correo = :correo');
        $buscar->execute(['correo' => $correo]);
        $usuario = $buscar->fetch();

        $bloqueado = $usuario
            && $usuario['bloqueado_hasta']
            && strtotime($usuario['bloqueado_hasta']) > time();

        if ($bloqueado) {
            $tipoMensaje = 'error';
            $mensaje = 'Cuenta bloqueada temporalmente por múltiples intentos fallidos. Intenta más tarde.';
        } else {

            $claveValida = $usuario
                && (bool) $usuario['activo']
                && password_verify($clave, $usuario['clave_hash']);

            $registrarIntento = $pdo->prepare(
                'INSERT INTO intentos_acceso (correo, ip, exitoso) VALUES (:correo, :ip, :exitoso)'
            );
            $registrarIntento->execute([
                'correo' => $correo,
                'ip' => $ip,
                'exitoso' => $claveValida ? 1 : 0,
            ]);

            if ($claveValida) {

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['ultima_actividad'] = time();

                header('Location: dashboard.php');
                exit;

            } else {

                if ($usuario) {
                    $contarFallidos = $pdo->prepare(
                        'SELECT COUNT(*) AS total FROM intentos_acceso
                         WHERE correo = :correo AND exitoso = 0
                         AND creado_en >= (NOW() - INTERVAL :minutos MINUTE)'
                    );
                    $contarFallidos->bindValue(':correo', $correo);
                    $contarFallidos->bindValue(':minutos', $ventanaMinutos, PDO::PARAM_INT);
                    $contarFallidos->execute();
                    $totalFallidos = (int) $contarFallidos->fetch()['total'];

                    if ($totalFallidos >= $maxIntentos) {
                        $bloquearHasta = date('Y-m-d H:i:s', time() + $ventanaMinutos * 60);
                        $actualizar = $pdo->prepare('UPDATE usuarios SET bloqueado_hasta = :hasta WHERE id = :id');
                        $actualizar->execute(['hasta' => $bloquearHasta, 'id' => $usuario['id']]);
                    }
                }

                $tipoMensaje = 'error';
                $mensaje = 'Correo o contraseña incorrectos.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Inicio de sesión de Sport Zone">
<title>Sport Zone | Iniciar sesión</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body class="login-page">
<main class="login-container">
<section class="login-card" aria-labelledby="login-title">
  <img class="login-logo" src="assets/img/logo.png" alt="Logo de Sport Zone">
  <header class="login-header">
    <h1 id="login-title">Bienvenido a Sport Zone</h1>
    <p>Inicia sesión para continuar</p>
  </header>

  <?php if ($mensaje): ?>
      <?php if ($tipoMensaje === 'exito'): ?>
          <div class="login-message" style="display:block;margin-bottom:var(--space-4)">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
          </div>
      <?php else: ?>
          <div class="field-error" style="display:block;margin-bottom:var(--space-4)">
            <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
          </div>
      <?php endif; ?>
  <?php endif; ?>

  <form method="post" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

    <div class="form-group">
      <label for="email">Correo electrónico</label>
      <input class="input" type="email" id="email" name="email" placeholder="ejemplo@correo.com" autocomplete="email" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña</label>
      <input class="input" type="password" id="password" name="password" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
    </div>

    <button class="login-button" type="submit">Iniciar sesión</button>
  </form>

  <div class="register-section">
    <p>¿No tienes una cuenta? <a href="registro.php">Crear cuenta</a></p>
  </div>
</section>
</main>
</body>
</html>