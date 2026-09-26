<?php
/**
 * Requiere que la página que lo incluye ya haya definido:
 * $usuario      -> array de usuarioActual()
 * $paginaActual -> id de la página activa (ej. 'dashboard', 'productos', 'usuarios')
 */
$opcionesMenu = require __DIR__ . '/../config/menu.php';
?>
<aside id="sidebar" class="sidebar">
    <h2 class="sidebar-title">Navegación</h2>
    <nav aria-label="Navegación principal">
        <?php foreach ($opcionesMenu as $opcion): ?>
            <?php if (in_array($usuario['rol'], $opcion['roles'], true)): ?>
                <a href="<?= htmlspecialchars($opcion['href'], ENT_QUOTES, 'UTF-8') ?>"
                   class="<?= $paginaActual === $opcion['id'] ? 'active' : '' ?>"
                   <?= $paginaActual === $opcion['id'] ? 'aria-current="page"' : '' ?>>
                    <?= htmlspecialchars($opcion['etiqueta'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    <p class="sidebar-note">Sport Zone v2.0 · Sesión de <?= htmlspecialchars(ucfirst($usuario['rol']), ENT_QUOTES, 'UTF-8') ?>.</p>
</aside>