<?php

class Cliente {
    

    public static function crear($nombre, $apellido, $dpi, $telefono, $fecha_nacimiento, $direccion = null, $email = null) {
        
        $existente = self::obtenerPorDPI($dpi);
        if ($existente) {
            return ['exito' => false, 'mensaje' => 'Ya existe un cliente con este DPI'];
        }
        
        // Validaciones
        if (empty($nombre) || empty($apellido) || empty($dpi) || empty($telefono) || empty($fecha_nacimiento)) {
            return ['exito' => false, 'mensaje' => 'Faltan campos obligatorios'];
        }
        
        try {
            $sql = "INSERT INTO clientes 
                    (nombre, apellido, dpi, telefono, direccion, fecha_nacimiento, email, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'activo')";
            
            $resultado = Database::ejecutar($sql, [
                htmlspecialchars($nombre),
                htmlspecialchars($apellido),
                htmlspecialchars($dpi),
                htmlspecialchars($telefono),
                htmlspecialchars($direccion ?? ''),
                $fecha_nacimiento,
                htmlspecialchars($email ?? '')
            ]);
            
            Database::registrarLog(
                'CREAR_CLIENTE',
                "Nuevo cliente: {$nombre} {$apellido}",
                $_SESSION['id_usuario'] ?? null,
                'clientes',
                $resultado['ultimo_id']
            );
            
            return [
                'exito' => true,
                'mensaje' => 'Cliente registrado exitosamente',
                'cliente_id' => $resultado['ultimo_id']
            ];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    

    public static function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM clientes WHERE id_cliente = ? AND estado = 'activo'";
            return Database::seleccionarUno($sql, [$id]);
        } catch (Exception $e) {
            return null;
        }
    }
    

    public static function obtenerPorDPI($dpi) {
        try {
            $sql = "SELECT * FROM clientes WHERE dpi = ? AND estado = 'activo'";
            return Database::seleccionarUno($sql, [$dpi]);
        } catch (Exception $e) {
            return null;
        }
    }
    

    public static function listar() {
        try {
            $sql = "SELECT * FROM clientes WHERE estado = 'activo' ORDER BY nombre, apellido ASC";
            return Database::seleccionar($sql);
        } catch (Exception $e) {
            return [];
        }
    }

    public static function buscarPorNombre($nombre) {
        try {
            $nombre = '%' . $nombre . '%';
            $sql = "SELECT * FROM clientes 
                    WHERE estado = 'activo' 
                    AND (nombre LIKE ? OR apellido LIKE ? OR dpi LIKE ?)
                    ORDER BY nombre, apellido ASC";
            return Database::seleccionar($sql, [$nombre, $nombre, $nombre]);
        } catch (Exception $e) {
            return [];
        }
    }
    
 
    public static function actualizar($id, $datos) {
        $cliente = self::obtenerPorId($id);
        if (!$cliente) {
            return ['exito' => false, 'mensaje' => 'Cliente no encontrado'];
        }
        
        try {
            $campos = [];
            $valores = [];
            
            if (isset($datos['nombre'])) {
                $campos[] = 'nombre = ?';
                $valores[] = htmlspecialchars($datos['nombre']);
            }
            if (isset($datos['apellido'])) {
                $campos[] = 'apellido = ?';
                $valores[] = htmlspecialchars($datos['apellido']);
            }
            if (isset($datos['telefono'])) {
                $campos[] = 'telefono = ?';
                $valores[] = htmlspecialchars($datos['telefono']);
            }
            if (isset($datos['email'])) {
                $campos[] = 'email = ?';
                $valores[] = htmlspecialchars($datos['email']);
            }
            if (isset($datos['direccion'])) {
                $campos[] = 'direccion = ?';
                $valores[] = htmlspecialchars($datos['direccion']);
            }
            if (isset($datos['fecha_nacimiento'])) {
                $campos[] = 'fecha_nacimiento = ?';
                $valores[] = $datos['fecha_nacimiento'];
            }
            
            if (empty($campos)) {
                return ['exito' => false, 'mensaje' => 'No hay datos para actualizar'];
            }
            
            $valores[] = $id;
            $sql = "UPDATE clientes SET " . implode(', ', $campos) . " WHERE id_cliente = ?";
            
            Database::ejecutar($sql, $valores);
            
            Database::registrarLog(
                'ACTUALIZAR_CLIENTE',
                "Cliente actualizado: {$cliente['nombre']}",
                $_SESSION['id_usuario'] ?? null,
                'clientes',
                $id
            );
            
            return ['exito' => true, 'mensaje' => 'Cliente actualizado exitosamente'];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    

    public static function esCumpleanos($cliente_id) {
        try {
            $cliente = self::obtenerPorId($cliente_id);
            if (!$cliente || !$cliente['fecha_nacimiento']) {
                return false;
            }
            
            $hoy = date('m-d');
            $cumple = date('m-d', strtotime($cliente['fecha_nacimiento']));
            
            return $hoy === $cumple;
        } catch (Exception $e) {
            return false;
        }
    }
    

    public static function obtenerEdad($cliente_id) {
        try {
            $cliente = self::obtenerPorId($cliente_id);
            if (!$cliente || !$cliente['fecha_nacimiento']) {
                return null;
            }
            
            $nacimiento = new DateTime($cliente['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento);
            
            return $edad->y;
        } catch (Exception $e) {
            return null;
        }
    }
}
?>