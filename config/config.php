<?php
// Rutas del sistema
define('BASE_URL', 'http://localhost/Sistema-PrePrivado/');
define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . 'app/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');

// Información del sistema
define('SISTEMA_NOMBRE', 'La Suerte - Sistema de Loterías');
define('EMPRESA', 'La Suerte');





date_default_timezone_set('America/Guatemala');

if (session_status() === PHP_SESSION_NONE) {
    // Configurar opciones ANTES de iniciar sesión
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    
    // Iniciar sesión
    session_start();
}


if ($_SERVER['SERVER_NAME'] === 'localhost') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}










function estaAutenticado() {
    return isset($_SESSION['id_usuario']);
}


function requiereAutenticacion() {
    if (!estaAutenticado()) {
       redirigir('auth/login');
    }
}


function requiereAdmin() {
    requiereAutenticacion();
    if ($_SESSION['rol'] !== 'administrador') {
        redirigir('dashboard');
    }
}
function redirigir($ruta) {
    header('Location: ' . BASE_URL . $ruta);
    exit;
}


function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}


function limpiar($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}


function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function validarTelefono($telefono) {
    return preg_match('/^[0-9]{8,15}$/', $telefono);
}


function validarDPI($dpi) {
    return preg_match('/^[0-9]{13,20}$/', $dpi);
}
?>