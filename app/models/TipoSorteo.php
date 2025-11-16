<?php

class TipoSorteo {

    public static function listarActivos() {
        try {
            $sql = "SELECT * FROM tipo_sorteo 
                    WHERE estado = 'activo' 
                    ORDER BY nombre";
            
            return Database::seleccionar($sql);
        } catch (Exception $e) {
            return [];
        }
    }

     public static function listar() {
        try {
            $sql = "SELECT * FROM tipo_sorteo ORDER BY nombre";
            return Database::seleccionar($sql);
        } catch (Exception $e) {
            return [];
        }
    }

      public static function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM tipo_sorteo WHERE id_tipo_sorteo = ?";
            return Database::seleccionarUno($sql, [$id]);
        } catch (Exception $e) {
            return null;
        }
    }


     public static function crear($datos) {
        try {
            $sql = "INSERT INTO tipo_sorteo 
                    (nombre, descripcion, premio_por_quetzal, sorteos_diarios, estado) 
                    VALUES (?, ?, ?, ?, 'activo')";
            
            $resultado = Database::ejecutar($sql, [
                htmlspecialchars($datos['nombre']),
                htmlspecialchars($datos['descripcion'] ?? ''),
                $datos['premio_por_quetzal'],
                $datos['sorteos_diarios']
            ]);
            
            Database::registrarLog(
                'CREAR_TIPO_SORTEO',
                "Nuevo tipo de sorteo: {$datos['nombre']}",
                $_SESSION['id_usuario'] ?? null,
                'tipo_sorteo',
                $resultado['ultimo_id']
            );
            
            return [
                'exito' => true,
                'mensaje' => 'Tipo de sorteo creado exitosamente',
                'id' => $resultado['ultimo_id']
            ];
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al crear tipo de sorteo: ' . $e->getMessage()
            ];
        }
    }

     public static function actualizar($id, $datos) {
        try {
            $campos = [];
            $valores = [];
            
            if (isset($datos['nombre'])) {
                $campos[] = 'nombre = ?';
                $valores[] = htmlspecialchars($datos['nombre']);
            }
            if (isset($datos['descripcion'])) {
                $campos[] = 'descripcion = ?';
                $valores[] = htmlspecialchars($datos['descripcion']);
            }
            if (isset($datos['premio_por_quetzal'])) {
                $campos[] = 'premio_por_quetzal = ?';
                $valores[] = $datos['premio_por_quetzal'];
            }
            if (isset($datos['sorteos_diarios'])) {
                $campos[] = 'sorteos_diarios = ?';
                $valores[] = $datos['sorteos_diarios'];
            }
            if (isset($datos['estado'])) {
                $campos[] = 'estado = ?';
                $valores[] = $datos['estado'];
            }
            
            if (empty($campos)) {
                return ['exito' => false, 'mensaje' => 'No hay datos para actualizar'];
            }
            
            $valores[] = $id;
            $sql = "UPDATE tipo_sorteo SET " . implode(', ', $campos) . " WHERE id_tipo_sorteo = ?";
            
            Database::ejecutar($sql, $valores);
            
            Database::registrarLog(
                'ACTUALIZAR_TIPO_SORTEO',
                "Tipo de sorteo actualizado ID: {$id}",
                $_SESSION['id_usuario'] ?? null,
                'tipo_sorteo',
                $id
            );
            
            return ['exito' => true, 'mensaje' => 'Tipo de sorteo actualizado exitosamente'];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }

}
?>