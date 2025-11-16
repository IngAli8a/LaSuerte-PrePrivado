<?php

class DashboardController extends Controller {
    
    public function __construct() {
        if (!Usuario::estaAutenticado()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    public function index() {
        $usuario = Usuario::obtenerActual();
        
        // Datos para la vista
        $datos = [
            'usuario' => $usuario
        ];
        
        $this->vista('layout/header');
        $this->vista('dashboard', $datos);
        $this->vista('layout/footer');
    }
}
?>