<?php

// Se debe configurar ANTES de iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,   // JS no puede leer la cookie
        'samesite' => 'Lax',  // mitiga CSRF básico
        // 'secure' => true,  // activar cuando el sitio corra en HTTPS
    ]);
    session_start();
}

const INACTIVIDAD_MAX = 1800; // segundos = 30 minutos

function estaAutenticado(): bool
{
    if (empty($_SESSION['usuario_id'])) {
        return false;
    }

    if (isset($_SESSION['ultima_actividad'])) {
        $inactivo = time() - $_SESSION['ultima_actividad'];

        if ($inactivo > INACTIVIDAD_MAX) {
            $_SESSION = [];
            session_unset();
            session_destroy();
            return false;
        }
    }

    $_SESSION['ultima_actividad'] = time();
    return true;
}

function usuarioActual(): ?array
{
    if (!estaAutenticado()) {
        return null;
    }

    return [
        'id' => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['usuario_nombre'],
        'rol' => $_SESSION['usuario_rol'],
    ];
}