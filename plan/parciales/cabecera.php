<?php
/**
 * Requiere que la página que lo incluye ya haya definido:
 * $usuario -> array de usuarioActual()
 */
?>
<header class="site-header">
    <a href="dashboard.php" aria-label="Ir al inicio"><img class="site-logo" src="assets/img/logo.png" alt="Logo Sport Zone"></a>
    <div class="user-info">
        <span><?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
        <span class="user-role"><?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8') ?></span>
        <a class="logout-link" href="salir.php">Salir</a>
    </div>
    <button id="btn-menu" class="menu-button" type="button"
            aria-label="Abrir menú" aria-expanded="false" aria-controls="sidebar">☰</button>
</header>