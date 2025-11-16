<?php

class ClienteController extends Controller {
    
    public function __construct() {
        $this->verificarAutenticacion();
    }
    

    public function crear() {
        $mensaje = $this->obtenerMensaje();
        
        $this->vista('layout/header');
        $this->vista('clientes/crear', ['mensaje' => $mensaje]);
        $this->vista('layout/footer');
    }
    

    public function procesarCrear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('cliente/crear');
        }
        
        $nombre = $this->post('nombre');
        $apellido = $this->post('apellido');
        $dpi = $this->post('dpi');
        $telefono = $this->post('telefono');
        $fecha_nacimiento = $this->post('fecha_nacimiento');
        $direccion = $this->post('direccion') ?: null;
        $email = $this->post('email') ?: null;
        
        // Validaciones
        if (empty($nombre) || empty($apellido) || empty($dpi) || empty($telefono) || empty($fecha_nacimiento)) {
            $this->establecerMensaje('error', 'Por favor completa todos los campos obligatorios');
            $this->redirigir('cliente/crear');
        }
        
        if (!validarDPI($dpi)) {
            $this->establecerMensaje('error', 'El DPI debe tener entre 13 y 20 dígitos');
            $this->redirigir('cliente/crear');
        }
        
        if (!validarTelefono($telefono)) {
            $this->establecerMensaje('error', 'El teléfono debe tener entre 8 y 15 dígitos');
            $this->redirigir('cliente/crear');
        }
        
        $resultado = Cliente::crear($nombre, $apellido, $dpi, $telefono, $fecha_nacimiento, $direccion, $email);
        
        if ($resultado['exito']) {
            $this->establecerMensaje('success', $resultado['mensaje']);
            $this->redirigir('cliente/listar');
        } else {
            $this->establecerMensaje('error', $resultado['mensaje']);
            $this->redirigir('cliente/crear');
        }
    }
    
 
    public function listar() {
        $clientes = Cliente::listar();
        $mensaje = $this->obtenerMensaje();
        
        $this->vista('layout/header');
        $this->vista('clientes/listar', [
            'clientes' => $clientes,
            'mensaje' => $mensaje
        ]);
        $this->vista('layout/footer');
    }
    

    public function editar() {
        $cliente_id = $this->get('id');
        
        if (empty($cliente_id)) {
            $this->redirigir('cliente/listar');
        }
        
        $cliente = Cliente::obtenerPorId($cliente_id);
        
        if (!$cliente) {
            $this->establecerMensaje('error', 'Cliente no encontrado');
            $this->redirigir('cliente/listar');
        }
        
        $mensaje = $this->obtenerMensaje();
        
        $this->vista('layout/header');
        $this->vista('clientes/editar', [
            'cliente' => $cliente,
            'mensaje' => $mensaje
        ]);
        $this->vista('layout/footer');
    }
    

    public function procesarEditar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('cliente/listar');
        }
        
        $cliente_id = $this->post('cliente_id');
        $nombre = $this->post('nombre');
        $apellido = $this->post('apellido');
        $telefono = $this->post('telefono');
        $email = $this->post('email');
        $direccion = $this->post('direccion');
        $fecha_nacimiento = $this->post('fecha_nacimiento');
        
        if (empty($nombre) || empty($apellido) || empty($telefono)) {
            $this->establecerMensaje('error', 'Por favor completa los campos obligatorios');
            $this->redirigir('cliente/editar?id=' . $cliente_id);
        }
        
        $resultado = Cliente::actualizar($cliente_id, [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'email' => $email,
            'direccion' => $direccion,
            'fecha_nacimiento' => $fecha_nacimiento
        ]);
        
        if ($resultado['exito']) {
            $this->establecerMensaje('success', $resultado['mensaje']);
            $this->redirigir('cliente/listar');
        } else {
            $this->establecerMensaje('error', $resultado['mensaje']);
            $this->redirigir('cliente/editar?id=' . $cliente_id);
        }
    }
    

    public function buscarPorDPI() {
        $dpi = $this->get('dpi');
        
        if (empty($dpi)) {
            $this->json(['exito' => false, 'mensaje' => 'DPI requerido'], 400);
        }
        
        $cliente = Cliente::obtenerPorDPI($dpi);
        
        if ($cliente) {
            $this->json([
                'exito' => true,
                'cliente' => $cliente
            ]);
        } else {
            $this->json([
                'exito' => false,
                'mensaje' => 'Cliente no encontrado'
            ], 404);
        }
    }
}
?>