<?php
// Cargar configuración global
require_once 'config/config.php';

// Cargar clases core
require_once CORE_PATH . 'Database.php';
require_once CORE_PATH . 'Controller.php';
require_once CORE_PATH . 'Route.php';

// Cargar models
require_once APP_PATH . 'models/Usuario.php';

// Cargar controllers
require_once APP_PATH . 'controllers/AuthController.php';
require_once APP_PATH . 'controllers/DashboardController.php';

Route::iniciar();
?>