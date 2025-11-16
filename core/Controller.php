<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Controller {
    
    protected $datos = [];
    
    public function vista($vista, $datos = []) {
        $this->datos = $datos;
    
        extract($this->datos);
        

        $ruta = APP_PATH . 'views/' . $vista . '.php';
        
        if (file_exists($ruta)) {
            include $ruta;
        } else {
            echo "Error: Vista no encontrada: $vista";
        }
    }
    

    public function json($datos, $codigo = 200) {
        header('Content-Type: application/json');
        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }
    

    public function redirigir($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    public function verificarAutenticacion() {
        if (!isset($_SESSION['id_usuario'])) {
            $this->redirigir('login');
       }
    }
    

    public function obtenerPost() {
        return $_POST;
    }
    

    public function obtenerGet() {
        return $_GET;
    }
    

    public function post($clave, $defecto = null) {
        return isset($_POST[$clave]) ? $_POST[$clave] : $defecto;
    }
    

    public function get($clave, $defecto = null) {
        return isset($_GET[$clave]) ? $_GET[$clave] : $defecto;
    }
    

    public function establecerMensaje($tipo, $mensaje) {
        $_SESSION['mensaje_tipo'] = $tipo; // success, error, warning, info
        $_SESSION['mensaje_texto'] = $mensaje;
    }
    

    public function obtenerMensaje() {
        if (isset($_SESSION['mensaje_tipo']) && isset($_SESSION['mensaje_texto'])) {
            $mensaje = [
                'tipo' => $_SESSION['mensaje_tipo'],
                'texto' => $_SESSION['mensaje_texto']
            ];
            
            unset($_SESSION['mensaje_tipo']);
            unset($_SESSION['mensaje_texto']);
            
            return $mensaje;
        }
        
        return null;
    }
    

    public function obtenerVendedorActual() {
        if (isset($_SESSION['id_usuario'])) {
            return [
                'id' => $_SESSION['id_usuario'],
                'nombre' => $_SESSION['nombre_completo'],
                'usuario' => $_SESSION['nombre_usuario']
            ];
        }
        return null;
    }
}
?>