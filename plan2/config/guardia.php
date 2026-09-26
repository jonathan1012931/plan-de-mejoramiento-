<?php

require __DIR__ . '/sesion.php';

if (!estaAutenticado()) {
    header('Location: login.php');
    exit;
}

function requerirRol(array $rolesPermitidos): void
{
    $usuario = usuarioActual();

    if (!$usuario || !in_array($usuario['rol'], $rolesPermitidos, true)) {
        http_response_code(403);
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
              <title>403 - Acceso denegado</title>
              <link rel="stylesheet" href="css/tokens.css">
              <link rel="stylesheet" href="css/stilos.css"></head>
              <body class="login-page"><main class="login-container">
              <section class="login-card"><h1>403</h1>
              <p>No tienes permisos para ver esta página.</p>
              <p><a href="dashboard.php">Volver al dashboard</a></p>
              </section></main></body></html>';
        exit;
    }
}