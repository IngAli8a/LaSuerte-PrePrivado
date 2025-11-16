<?php

class ApuestaController extends Controller {
    
    public function __construct() {
        $this->verificarAutenticacion();
    }
    

    public function crear() {

        $tipos_sorteo = TipoSorteo::listarActivos();
        
        $mensaje = $this->obtenerMensaje();
        
        $this->vista('layout/header');
        $this->vista('apuestas/crear', [
            'tipos_sorteo' => $tipos_sorteo,
            'mensaje' => $mensaje
        ]);
        $this->vista('layout/footer');
    }
    

    public function procesarCrear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('apuesta/crear');
        }
        
        $id_cliente = $this->post('id_cliente');
        $id_sorteo = $this->post('id_sorteo');
        $numero_apostado = $this->post('numero_apostado');
        $monto_apostado = $this->post('monto_apostado');
        $es_cumpleanos = $this->post('es_cumpleanos') == '1';
        $id_usuario = $_SESSION['id_usuario'];
        
        // Validaciones
        if (empty($id_cliente) || empty($id_sorteo) || empty($numero_apostado) || empty($monto_apostado)) {
            $this->establecerMensaje('error', 'Por favor completa todos los campos obligatorios');
            $this->redirigir('apuesta/crear');
        }
        
        // Validar número apostado
        if ($numero_apostado < 0 || $numero_apostado > 99) {
            $this->establecerMensaje('error', 'El número apostado debe estar entre 00 y 99');
            $this->redirigir('apuesta/crear');
        }
        
        // Validar monto
        if ($monto_apostado <= 0) {
            $this->establecerMensaje('error', 'El monto apostado debe ser mayor a 0');
            $this->redirigir('apuesta/crear');
        }
        
        // Verificar que el sorteo esté en estado abierto
        $sorteo = Sorteo::obtenerPorId($id_sorteo);
        if (!$sorteo || ($sorteo['estado'] != 'ABIERTO_VENTAS' && $sorteo['estado'] != 'PROGRAMADO')) {
            $this->establecerMensaje('error', 'El sorteo seleccionado no está disponible para apuestas');
            $this->redirigir('apuesta/crear');
        }
        
        // Obtener el multiplicador del premio
        $tipo_sorteo = TipoSorteo::obtenerPorId($sorteo['id_tipo_sorteo']);
        $premio_por_quetzal = $tipo_sorteo['premio_por_quetzal'];
        
        // Calcular premio potencial
        $premio_potencial = $monto_apostado * $premio_por_quetzal;
        $bono_cumpleanos = 0;
        
        if ($es_cumpleanos) {
            $bono_cumpleanos = $premio_potencial * 0.10;
            $premio_potencial += $bono_cumpleanos;
        }
        
        // Generar código de voucher único
        $codigo_voucher = $this->generarCodigoVoucher($sorteo['id_tipo_sorteo']);
        
        // Crear la apuesta
        $resultado = Apuesta::crear([
            'id_cliente' => $id_cliente,
            'id_sorteo' => $id_sorteo,
            'id_usuario' => $id_usuario,
            'numero_apostado' => $numero_apostado,
            'monto_apostado' => $monto_apostado,
            'premio_potencial' => $premio_potencial,
            'es_cumpleanos' => $es_cumpleanos,
            'bono_cumpleanos' => $bono_cumpleanos,
            'codigo_voucher' => $codigo_voucher
        ]);
        
        if ($resultado['exito']) {
            // Actualizar el total recaudado del sorteo
            Sorteo::actualizarRecaudado($id_sorteo, $monto_apostado);
            
            // Redirigir al voucher
            $this->redirigir('apuesta/voucher?codigo=' . $codigo_voucher);
        } else {
            $this->establecerMensaje('error', $resultado['mensaje']);
            $this->redirigir('apuesta/crear');
        }
    }
    

    private function generarCodigoVoucher($id_tipo_sorteo) {
        // Obtener prefijo según tipo de sorteo
        $tipo = TipoSorteo::obtenerPorId($id_tipo_sorteo);
        $prefijo = strtoupper(substr($tipo['nombre'], 0, 2));
        
        // Generar código: LS-YYYYMMDD-NNNNNN
        $fecha = date('Ymd');
        $random = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        
        $codigo = "{$prefijo}-{$fecha}-{$random}";
        
        // Verificar que no exista
        $existe = Apuesta::existeVoucher($codigo);
        if ($existe) {
            // Regenerar si existe
            return $this->generarCodigoVoucher($id_tipo_sorteo);
        }
        
        return $codigo;
    }
    
  
    public function voucher() {
        $codigo = $this->get('codigo');
        
        if (empty($codigo)) {
            $this->redirigir('apuesta/listar');
        }
        
        $apuesta = Apuesta::obtenerPorVoucher($codigo);
        
        if (!$apuesta) {
            $this->establecerMensaje('error', 'Voucher no encontrado');
            $this->redirigir('apuesta/listar');
        }
        
        $this->vista('layout/header');
        $this->vista('apuestas/voucher', ['apuesta' => $apuesta]);
        $this->vista('layout/footer');
    }
}
?>