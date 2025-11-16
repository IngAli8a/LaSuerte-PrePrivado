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





    public function logout() {
     Usuario::logout();
        redirigir('auth/login');
}

}
?>