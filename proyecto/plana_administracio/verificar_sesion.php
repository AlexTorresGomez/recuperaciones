<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$tiempoInactividad = 600; // 10 minutos

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $tiempoInactividad)) {
    session_unset();
    session_destroy();
    header("Location: login.php?mensaje=sesion_caducada");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

// Verificamos si hay un usuario logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?mensaje=acceso_denegado");
    exit();
}
?>
