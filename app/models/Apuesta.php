<?php

class Apuesta {
 
    public static function crear($datos) {
        try {
            $sql = "INSERT INTO apuestas (
                        id_cliente, id_sorteo, id_usuario, numero_apostado, 
                        monto_apostado, premio_potencial, es_cumpleanos, 
                        bono_cumpleanos, codigo_voucher, estado
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'REGISTRADA')";
            
            $resultado = Database::ejecutar($sql, [
                $datos['id_cliente'],
                $datos['id_sorteo'],
                $datos['id_usuario'],
                $datos['numero_apostado'],
                $datos['monto_apostado'],
                $datos['premio_potencial'],
                $datos['es_cumpleanos'] ? 1 : 0,
                $datos['bono_cumpleanos'],
                $datos['codigo_voucher']
            ]);
            
            Database::registrarLog(
                'CREAR_APUESTA',
                "Nueva apuesta: {$datos['codigo_voucher']}",
                $_SESSION['id_usuario'] ?? null,
                'apuestas',
                $resultado['ultimo_id']
            );
            
            return [
                'exito' => true,
                'mensaje' => 'Apuesta registrada exitosamente',
                'id_apuesta' => $resultado['ultimo_id']
            ];
            
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al registrar la apuesta: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verificar si existe un voucher
     */
    public static function existeVoucher($codigo) {
        try {
            $sql = "SELECT COUNT(*) as total FROM apuestas WHERE codigo_voucher = ?";
            $resultado = Database::seleccionarUno($sql, [$codigo]);
            return $resultado['total'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    

    public static function obtenerPorVoucher($codigo) {
        try {
            $sql = "SELECT 
                        a.*,
                        c.nombre, c.apellido, c.dpi, c.telefono,
                        s.fecha_sorteo, s.numero_sorteo_dia, s.numero_ganador,
                        ts.nombre as tipo_sorteo, ts.premio_por_quetzal,
                        u.nombre_completo as vendedor
                    FROM apuestas a
                    INNER JOIN clientes c ON a.id_cliente = c.id_cliente
                    INNER JOIN sorteos s ON a.id_sorteo = s.id_sorteo
                    INNER JOIN tipo_sorteo ts ON s.id_tipo_sorteo = ts.id_tipo_sorteo
                    INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
                    WHERE a.codigo_voucher = ?";
            
            return Database::seleccionarUno($sql, [$codigo]);
        } catch (Exception $e) {
            return null;
        }
    }
    

    public static function obtenerPorId($id) {
        try {
            $sql = "SELECT 
                        a.*,
                        c.nombre, c.apellido,
                        s.fecha_sorteo, s.numero_sorteo_dia,
                        ts.nombre as tipo_sorteo
                    FROM apuestas a
                    INNER JOIN clientes c ON a.id_cliente = c.id_cliente
                    INNER JOIN sorteos s ON a.id_sorteo = s.id_sorteo
                    INNER JOIN tipo_sorteo ts ON s.id_tipo_sorteo = ts.id_tipo_sorteo
                    WHERE a.id_apuesta = ?";
            
            return Database::seleccionarUno($sql, [$id]);
        } catch (Exception $e) {
            return null;
        }
    }
    
    public static function listar($filtros = []) {
        try {
            $sql = "SELECT 
                        a.*,
                        c.nombre, c.apellido, c.dpi,
                        s.fecha_sorteo, s.numero_sorteo_dia, s.numero_ganador,
                        ts.nombre as tipo_sorteo,
                        u.nombre_completo as vendedor
                    FROM apuestas a
                    INNER JOIN clientes c ON a.id_cliente = c.id_cliente
                    INNER JOIN sorteos s ON a.id_sorteo = s.id_sorteo
                    INNER JOIN tipo_sorteo ts ON s.id_tipo_sorteo = ts.id_tipo_sorteo
                    INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
                    WHERE 1=1";
            
            $params = [];
            
            // Filtro por fecha
            if (!empty($filtros['fecha'])) {
                $sql .= " AND DATE(a.fecha_hora_apuesta) = ?";
                $params[] = $filtros['fecha'];
            }
            
            // Filtro por sorteo
            if (!empty($filtros['sorteo'])) {
                $sql .= " AND a.id_sorteo = ?";
                $params[] = $filtros['sorteo'];
            }
            
            // Filtro por estado
            if (!empty($filtros['estado'])) {
                $sql .= " AND a.estado = ?";
                $params[] = $filtros['estado'];
            }
            
            $sql .= " ORDER BY a.fecha_hora_apuesta DESC";
            
            return Database::seleccionar($sql, $params);
        } catch (Exception $e) {
            return [];
        }
    }


    public static function obtenerEstadisticasDia($fecha) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_apuestas,
                        SUM(monto_apostado) as total_recaudado,
                        AVG(monto_apostado) as promedio_apuesta,
                        COUNT(DISTINCT id_cliente) as clientes_unicos,
                        SUM(CASE WHEN es_cumpleanos = 1 THEN 1 ELSE 0 END) as con_cumpleanos
                    FROM apuestas
                    WHERE DATE(fecha_hora_apuesta) = ?
                    AND estado != 'ANULADA'";
            
            return Database::seleccionarUno($sql, [$fecha]);
        } catch (Exception $e) {
            return null;
        }
    }
    

    public static function anular($id_apuesta, $motivo) {
        try {
            $sql = "UPDATE apuestas 
                    SET estado = 'ANULADA'
                    WHERE id_apuesta = ?
                    AND estado = 'REGISTRADA'";
            
            Database::ejecutar($sql, [$id_apuesta]);
            
            Database::registrarLog(
                'ANULAR_APUESTA',
                "Apuesta anulada: {$motivo}",
                $_SESSION['id_usuario'] ?? null,
                'apuestas',
                $id_apuesta
            );
            
            return [
                'exito' => true,
                'mensaje' => 'Apuesta anulada correctamente'
            ];
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al anular la apuesta: ' . $e->getMessage()
            ];
        }
    }
    
   
    public static function historialCliente($cliente_id) {
        try {
            $sql = "SELECT 
                        a.*,
                        s.fecha_sorteo, s.numero_sorteo_dia, s.numero_ganador,
                        ts.nombre as tipo_sorteo
                    FROM apuestas a
                    INNER JOIN sorteos s ON a.id_sorteo = s.id_sorteo
                    INNER JOIN tipo_sorteo ts ON s.id_tipo_sorteo = ts.id_tipo_sorteo
                    WHERE a.id_cliente = ?
                    ORDER BY a.fecha_hora_apuesta DESC
                    LIMIT 50";
            
            return Database::seleccionar($sql, [$cliente_id]);
        } catch (Exception $e) {
            return [];
        }
    }
    
 
    public static function estadisticasCliente($cliente_id) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_apuestas,
                        SUM(monto_apostado) as total_apostado,
                        SUM(CASE WHEN estado = 'GANADORA' THEN premio_potencial ELSE 0 END) as total_ganado,
                        COUNT(CASE WHEN estado = 'GANADORA' THEN 1 END) as apuestas_ganadoras,
                        COUNT(CASE WHEN estado = 'PERDEDORA' THEN 1 END) as apuestas_perdedoras
                    FROM apuestas
                    WHERE id_cliente = ?
                    AND estado != 'ANULADA'";
            
            return Database::seleccionarUno($sql, [$cliente_id]);
        } catch (Exception $e) {
            return null;
        }
    }

    public static function actualizarEstadosPorSorteo($id_sorteo, $numero_ganador) {
        try {
            // Marcar ganadoras
            $sql = "UPDATE apuestas 
                    SET estado = 'GANADORA'
                    WHERE id_sorteo = ?
                    AND numero_apostado = ?
                    AND estado = 'REGISTRADA'";
            
            Database::ejecutar($sql, [$id_sorteo, $numero_ganador]);
            
            // Contar ganadoras
            $sqlCount = "SELECT COUNT(*) as total FROM apuestas 
                         WHERE id_sorteo = ? AND numero_apostado = ? AND estado = 'GANADORA'";
            $resultado = Database::seleccionarUno($sqlCount, [$id_sorteo, $numero_ganador]);
            $ganadoras = $resultado['total'];
            
            // Marcar perdedoras
            $sql = "UPDATE apuestas 
                    SET estado = 'PERDEDORA'
                    WHERE id_sorteo = ?
                    AND numero_apostado != ?
                    AND estado = 'REGISTRADA'";
            
            Database::ejecutar($sql, [$id_sorteo, $numero_ganador]);
            
            return [
                'exito' => true,
                'ganadoras' => $ganadoras,
                'mensaje' => "Se encontraron {$ganadoras} apuestas ganadoras"
            ];
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al actualizar estados: ' . $e->getMessage()
            ];
        }
    }


}
?>