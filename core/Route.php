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
    
}
?>