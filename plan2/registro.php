<?php
require __DIR__ . '/config/conexion.php';

$errores = [];
$valores = ['nombre' => '', 'correo' => '', 'rol' => 'consultor'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $valores['nombre'] = trim($_POST['nombre'] ?? '');
    $valores['correo'] = trim($_POST['correo'] ?? '');
    $valores['rol'] = $_POST['rol'] ?? 'consultor';
    $clave = $_POST['clave'] ?? '';

    if (mb_strlen($valores['nombre']) < 3) {
        $errores[] = 'El nombre debe tener mínimo 3 caracteres.';
    }
    if (!filter_var($valores['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no es válido.';
    }
    if (mb_strlen($clave) < 8) {
        $errores[] = 'La contraseña debe tener mínimo 8 caracteres.';
    }
    if (!in_array($valores['rol'], ['administrador', 'vendedor', 'consultor'], true)) {
        $errores[] = 'Rol no válido.';
    }

    if (empty($errores)) {
        $pdo = Conexion::obtener();

        $verificar = $pdo->prepare('SELECT id FROM usuarios WHERE correo = :correo');
        $verificar->execute(['correo' => $valores['correo']]);

        if ($verificar->fetch()) {
            $errores[] = 'Ese correo ya está registrado.';
        } else {
            $claveHash = password_hash($clave, PASSWORD_BCRYPT);

            $insertar = $pdo->prepare(
                'INSERT INTO usuarios (nombre, correo, clave_hash, rol, activo)
                 VALUES (:nombre, :correo, :clave_hash, :rol, 1)'
            );
            $insertar->execute([
                'nombre' => $valores['nombre'],
                'correo' => $valores['correo'],
                'clave_hash' => $claveHash,
                'rol' => $valores['rol'],
            ]);

            header('Location: login.php?registrado=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sport Zone | Registro</title>
<link rel="stylesheet" href="css/tokens.css">
<link rel="stylesheet" href="css/stilos.css">
</head>
<body class="login-page">
<main class="login-container">
<section class="login-card" aria-labelledby="titulo-registro">
    <header class="login-header">
        <h1 id="titulo-registro">Crear cuenta</h1>
        <p>Registro de usuarios de Sport Zone</p>
    </header>

    <?php if (!empty($errores)): ?>
        <div class="field-error" style="display:block;margin-bottom:var(--space-4)">
            <ul style="margin:0;padding-left:1.1rem">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="form-group">
            <label for="nombre">Nombre completo</label>
            <input class="input" type="text" id="nombre" name="nombre"
                   value="<?= htmlspecialchars($valores['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input class="input" type="email" id="correo" name="correo"
                   value="<?= htmlspecialchars($valores['correo'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select class="select" id="rol" name="rol">
                <option value="consultor" <?= $valores['rol'] === 'consultor' ? 'selected' : '' ?>>Consultor</option>
                <option value="vendedor" <?= $valores['rol'] === 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
                <option value="administrador" <?= $valores['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <div class="form-group">
            <label for="clave">Contraseña</label>
            <input class="input" type="password" id="clave" name="clave" minlength="8" required>
        </div>

        <button class="login-button" type="submit">Registrarme</button>
    </form>

    <div class="register-section">
        <p><a href="login.php">Ya tengo una cuenta</a></p>
    </div>
</section>
</main>
</body>
</html>