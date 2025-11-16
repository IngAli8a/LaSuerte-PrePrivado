<?php

class SorteoController extends Controller {
    
    public function __construct() {
        $this->verificarAutenticacion();
    }

    public function obtenerSorteosActivos() {
        $tipo_id = $this->get('tipo_id');
        
        if (empty($tipo_id)) {
            header('Content-Type: application/json');
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Tipo de sorteo requerido'
            ]);
            exit;
        }
        
        // Obtener sorteos activos del día
        $sorteos = Sorteo::obtenerSorteosActivos($tipo_id);
        
        header('Content-Type: application/json');
        if (!empty($sorteos)) {
            echo json_encode([
                'exito' => true,
                'sorteos' => $sorteos
            ]);
        } else {
            echo json_encode([
                'exito' => false,
                'mensaje' => 'No hay sorteos disponibles para hoy',
                'sorteos' => []
            ]);
        }
        exit;
    }
}


?>