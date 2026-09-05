<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/**
 * Comprueba si existe una sesión de administrador.
 */
function adminEstaAutenticado()
{
    return isset($_SESSION['admin_id'])
        && isset($_SESSION['admin_rol'])
        && $_SESSION['admin_rol'] === 'admin';
}


/**
 * Protege las páginas del panel administrativo.
 */
function protegerAdmin()
{
    if (!adminEstaAutenticado()) {
        header('Location: admin-login.php');
        exit;
    }
}


/**
 * Cierra la sesión del administrador.
 */
function cerrarSesionAdmin()
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}