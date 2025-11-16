<?php
class AuthController extends Controller {

    public function login() {
         if (Usuario::estaAutenticado()) {
        redirigir('dashboard');
        }
        
        $mensaje = $this->obtenerMensaje();
        
        $this->vista('layout/header');
        $this->vista('auth/login', ['mensaje' => $mensaje]);
        $this->vista('layout/footer');
    }

   public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('auth/login');
        }

        $nombre_usuario = $_POST['nombre_usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($nombre_usuario) || empty($password)) {
            $this->establecerMensaje('error', 'Por favor completa todos los campos');
            redirigir('auth/login');
        }

        $resultado = Usuario::login($nombre_usuario, $password);

        if ($resultado['exito']) {
            redirigir('dashboard'); 
        } else {
            $this->establecerMensaje('error', $resultado['mensaje']);
            redirigir('auth/login');
        }
    }

 public function registro() {
        if (Usuario::estaAutenticado()) {
           redirigir('dashboard');
        }
        
        $mensaje = $this->obtenerMensaje();
        
        $this->vista('layout/header');
        $this->vista('auth/registro', ['mensaje' => $mensaje]);
        $this->vista('layout/footer');
    }
    
 
   public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('auth/registro');
        }

        $nombre_completo = $_POST['nombre_completo'] ?? '';
        $nombre_usuario = $_POST['nombre_usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($nombre_completo) || empty($nombre_usuario) || empty($password) || empty($password_confirm)) {
            $this->establecerMensaje('error', 'Por favor completa todos los campos');
            redirigir('auth/registro');
        }

        if ($password !== $password_confirm) {
            $this->establecerMensaje('error', 'Las contraseñas no coinciden');
            redirigir('auth/registro');
        }

        if (strlen($nombre_usuario) < 4) {
            $this->establecerMensaje('error', 'El usuario debe tener al menos 4 caracteres');
            redirigir('auth/registro');
        }

        if (strlen($password) < 6) {
            $this->establecerMensaje('error', 'La contraseña debe tener al menos 6 caracteres');
            redirigir('auth/registro');
        }

        $resultado = Usuario::crear($nombre_usuario, $password, $nombre_completo, 'vendedor');

        if ($resultado['exito']) {
            $this->establecerMensaje('success', 'Registro exitoso. Por favor inicia sesión');
            redirigir('auth/login');
        } else {
            $this->establecerMensaje('error', $resultado['mensaje']);
            redirigir('auth/registro');
        }
    }



    public function logout() {
     Usuario::logout();
        redirigir('auth/login');
}

}
?>