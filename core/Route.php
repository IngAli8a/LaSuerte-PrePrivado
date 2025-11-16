<?php

class Route {
    
    private static $controlador = 'AuthController';
    private static $accion = 'login';
    private static $parametros = [];
    

    public static function iniciar() {
        try {

            $url = isset($_GET['url']) ? $_GET['url'] : '';
            
        
            if (empty($url)) {
                if (isset($_SESSION['id_usuario'])) {
                    $url = 'dashboard';
                } else {
                    $url = 'auth/login';
                }
            }
            

            $url = rtrim($url, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $partes = explode('/', $url);
            

            self::procesarUrl($partes);
            self::aplicarMapeos();
            self::ejecutar();
            
        } catch (Exception $e) {
            self::mostrarError($e->getMessage());
        }
    }
    

    private static function procesarUrl($partes) {

        if (isset($partes[0]) && !empty($partes[0])) {
            $nombre = ucfirst(strtolower($partes[0]));
            self::$controlador = $nombre . 'Controller';
            self::$accion = 'index'; 
        }
        

        if (isset($partes[1]) && !empty($partes[1])) {
            self::$accion = strtolower($partes[1]);
        }
        

        if (isset($partes[2])) {
            for ($i = 2; $i < count($partes); $i++) {
                self::$parametros[] = $partes[$i];
            }
        }
    }
    

    private static function aplicarMapeos() {

        $mapeos = [
            'AuthController' => [
                'index' => 'login',    // auth sin acción → login
                'login' => 'login',
                'registro' => 'registro',
                'logout' => 'logout',
                'procesarlogin' => 'procesarLogin',
                'procesarregistro' => 'procesarRegistro',
            ],
            'DashboardController' => [
                'index' => 'index',    
            ],
            'ClienteController' => [
                'index' => 'listar',   
            ],
            'ApuestaController' => [
                'index' => 'listar',   
            ],
        ];
        

        if (isset($mapeos[self::$controlador][self::$accion])) {
            self::$accion = $mapeos[self::$controlador][self::$accion];
        }
        
        $rutas_especiales = [
            'login' => ['AuthController', 'login'],
            'registro' => ['AuthController', 'registro'],
            'logout' => ['AuthController', 'logout'],
            'dashboard' => ['DashboardController', 'index'],
        ];
        

        $controlador_actual = strtolower(str_replace('Controller', '', self::$controlador));
        if (isset($rutas_especiales[$controlador_actual])) {
            list(self::$controlador, self::$accion) = $rutas_especiales[$controlador_actual];
        }
    }

        private static function ejecutar() {
        
        if (!class_exists(self::$controlador)) {
            self::mostrarError("Controlador no encontrado: " . self::$controlador . 
                             "\nRuta: " . self::obtenerRutaActual());
            return;
        }
        

        try {
            $controlador = new self::$controlador();
        } catch (Exception $e) {
            self::mostrarError("Error al instanciar controlador: " . $e->getMessage());
            return;
        }
        
        // Verificar que el método existe
        if (!method_exists($controlador, self::$accion)) {
            self::mostrarError("Acción no encontrada: " . self::$accion . " en " . self::$controlador);
            return;
        }
        
        // Llamar al método con los parámetros
        if (!empty(self::$parametros)) {
            call_user_func_array(
                [$controlador, self::$accion],
                self::$parametros
            );
        } else {
            $controlador->{self::$accion}();
        }
    }
    

    public static function obtenerRutaActual() {
        return isset($_GET['url']) ? $_GET['url'] : 'dashboard';
    }
    

    public static function obtenerControlador() {
        return self::$controlador;
    }
    

    public static function obtenerAccion() {
        return self::$accion;
    }
    

    public static function obtenerParametros() {
        return self::$parametros;
    }
    

    public static function es($controlador, $accion = null) {
        $nombre_controlador = ucfirst(strtolower($controlador)) . 'Controller';
        
        if ($accion) {
            return self::$controlador === $nombre_controlador && 
                   self::$accion === strtolower($accion);
        }
        
        return self::$controlador === $nombre_controlador;
    }
    

    private static function mostrarError($mensaje) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0"><
            <title>Error - Sistema de Loterías</title>
           
        </head>
        <body>
            <div class="error-container">
                <h1> Error</h1>
                <h2>Ruta no encontrada</h2>
                
                <div class="error-details">
                    <strong>Mensaje de Error:</strong><br>
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
                
                <div class="debug-info">
                    <strong>Información de Debug:</strong><br>
                    Controlador: <?php echo htmlspecialchars(Route::obtenerControlador()); ?><br>
                    Acción: <?php echo htmlspecialchars(Route::obtenerAccion()); ?><br>
                    Ruta: <?php echo htmlspecialchars(Route::obtenerRutaActual()); ?>
                </div>
                
                <p>
                    <a href="<?php echo BASE_URL; ?>login" class="btn-home">
                         Ir a Login
                    </a>
                    <a href="<?php echo BASE_URL; ?>debug_sesion.php" class="btn-debug">
                         Ver Debug
                    </a>
                </p>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
    
}
?>