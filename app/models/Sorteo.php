<?php

class Sorteo {
    

    public static function obtenerPorId($id) {
        try {
            $sql = "SELECT s.*, ts.nombre as tipo_sorteo_nombre, ts.premio_por_quetzal
                    FROM sorteos s
                    INNER JOIN tipo_sorteo ts ON s.id_tipo_sorteo = ts.id_tipo_sorteo
                    WHERE s.id_sorteo = ?";
            
            return Database::seleccionarUno($sql, [$id]);
        } catch (Exception $e) {
            return null;
        }
    }

    public static function obtenerSorteosActivos($id_tipo_sorteo) {
        try {
            $sql = "SELECT * FROM sorteos 
                    WHERE id_tipo_sorteo = ?
                    AND fecha_sorteo = CURDATE()
                    AND (estado = 'PROGRAMADO' OR estado = 'ABIERTO_VENTAS')
                    ORDER BY numero_sorteo_dia";
            
            return Database::seleccionar($sql, [$id_tipo_sorteo]);
        } catch (Exception $e) {
            return [];
        }
    }

    public static function actualizarRecaudado($id_sorteo, $monto) {
        try {
            $sql = "UPDATE sorteos 
                    SET total_recaudado = total_recaudado + ?
                    WHERE id_sorteo = ?";
            
            Database::ejecutar($sql, [$monto, $id_sorteo]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    

}
?>