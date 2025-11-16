<?php

class Database {
    
    private static $conexion = null;
    
    // Configuración de la base de datos
    private const DB_HOST = 'localhost';
    private const DB_USER = 'root';
    private const DB_PASSWORD = '';
    private const DB_NAME = 'sorteos_la_suerte';
    private const DB_CHARSET = 'utf8mb4';
    

    public static function conexion() {
        if (self::$conexion === null) {
            try {
                self::$conexion = new mysqli(
                    self::DB_HOST,
                    self::DB_USER,
                    self::DB_PASSWORD,
                    self::DB_NAME
                );
                
                // Configurar charset
                self::$conexion->set_charset(self::DB_CHARSET);
                
                // Verificar conexión
                if (self::$conexion->connect_error) {
                    throw new Exception('Error de conexión: ' . self::$conexion->connect_error);
                }
            } catch (Exception $e) {
                die('Error al conectar: ' . $e->getMessage());
            }
        }
        
        return self::$conexion;
    }
    

    public static function seleccionar($sql, $parametros = []) {
        $conn = self::conexion();
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception('Error en la consulta: ' . $conn->error);
        }
        
        // Bindear parámetros si existen
        if (!empty($parametros)) {
            self::bindearParametros($stmt, $parametros);
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $datos;
    }
    

    public static function seleccionarUno($sql, $parametros = []) {
        $resultados = self::seleccionar($sql, $parametros);
        return !empty($resultados) ? $resultados[0] : null;
    }
    
 
    public static function ejecutar($sql, $parametros = []) {
        $conn = self::conexion();
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception('Error en la consulta: ' . $conn->error);
        }
        
        // Bindear parámetros
        if (!empty($parametros)) {
            self::bindearParametros($stmt, $parametros);
        }
        
        if (!$stmt->execute()) {
            throw new Exception('Error al ejecutar: ' . $stmt->error);
        }
        
        $filas_afectadas = $stmt->affected_rows;
        $ultimo_id = $stmt->insert_id;
        $stmt->close();
        
        return [
            'filas_afectadas' => $filas_afectadas,
            'ultimo_id' => $ultimo_id,
            'error' => false
        ];
    }
    

    private static function bindearParametros(&$stmt, $parametros) {
        if (empty($parametros)) {
            return;
        }
        
        // Determinar tipos de datos
        $tipos = '';
        foreach ($parametros as $valor) {
            if (is_int($valor)) {
                $tipos .= 'i';
            } elseif (is_float($valor)) {
                $tipos .= 'd';
            } elseif (is_bool($valor)) {
                $tipos .= 'i';
            } else {
                $tipos .= 's';
            }
        }
        

        $referencias = [];
        foreach ($parametros as &$valor) {
            $referencias[] = &$valor;
        }
        

        array_unshift($referencias, $tipos);
        

        call_user_func_array([$stmt, 'bind_param'], $referencias);
    }
    

    public static function iniciarTransaccion() {
        $conn = self::conexion();
        return $conn->begin_transaction();
    }
    

    public static function confirmar() {
        $conn = self::conexion();
        return $conn->commit();
    }
    

    public static function revertir() {
        $conn = self::conexion();
        return $conn->rollback();
    }
    

    public static function error() {
        $conn = self::conexion();
        return $conn->error;
    }

    public static function registrarLog($tipo, $descripcion, $id_usuario = null, $entidad_afectada = null, $id_entidad = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $user_agent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido', 0, 255);
        
        $sql = "INSERT INTO log_transacciones 
                (id_usuario, tipo_transaccion, descripcion, entidad_afectada, id_entidad, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try {
            self::ejecutar($sql, [
                $id_usuario,
                $tipo,
                $descripcion,
                $entidad_afectada,
                $id_entidad,
                $ip,
                $user_agent
            ]);
        } catch (Exception $e) {
            // Silenciosamente ignorar errores en logs
            error_log('Error al registrar log: ' . $e->getMessage());
        }
    }

    public static function cerrar() {
        if (self::$conexion !== null) {
            self::$conexion->close();
            self::$conexion = null;
        }
    }
}


register_shutdown_function([Database::class, 'cerrar']);
?>