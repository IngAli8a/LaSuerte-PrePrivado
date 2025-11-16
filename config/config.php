<?php
// Rutas del sistema
define('BASE_URL', 'http://localhost/LaSuerte-PrePrivado/');
define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . 'app/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');

// Información del sistema
define('SISTEMA_NOMBRE', 'La Suerte - Sistema de Loterías');
define('EMPRESA', 'La Suerte');


$SORTEOS = [
    1 => [
        'id' => 1,
        'nombre' => 'La Santa',
        'premio' => 25,
        'diarios' => 3
    ],
    2 => [
        'id' => 2,
        'nombre' => 'La Rifa',
        'premio' => 70,
        'diarios' => 1
    ],
    3 => [
        'id' => 3,
        'nombre' => 'El Sorteo',
        'premio' => 150,
        'diarios' => 2
    ]
];


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


function obtenerSorteos() {
    global $SORTEOS;
    return $SORTEOS;
}


function obtenerSorteo($id) {
    global $SORTEOS;
    return isset($SORTEOS[$id]) ? $SORTEOS[$id] : null;
}


function formatearMoneda($monto) {
    return 'Q' . number_format($monto, 2);
}


function formatearFecha($fecha, $formato = 'd/m/Y') {
    return date($formato, strtotime($fecha));
}


function esHabil($fecha) {
    $dia_semana = date('N', strtotime($fecha));
    return !($dia_semana == 6 || $dia_semana == 7);
}



function obtenerIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var($ip, FILTER_VALIDATE_IP);
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