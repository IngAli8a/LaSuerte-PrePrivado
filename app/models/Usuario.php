<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Usuario {

    public static function crear($nombre_usuario, $password, $nombre_completo, $rol = 'vendedor') {
        // Validar que el usuario no exista
        $existente = self::obtenerPorUsuario($nombre_usuario);
        if ($existente) {
            return ['exito' => false, 'mensaje' => 'El usuario ya existe'];
        }
        
        // Validar rol
        $roles_validos = ['administrador', 'vendedor'];
        if (!in_array($rol, $roles_validos)) {
            return ['exito' => false, 'mensaje' => 'Rol inválido'];
        }
        
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios 
                    (nombre_usuario, password, nombre_completo, rol, estado) 
                    VALUES (?, ?, ?, ?, 'activo')";
            
            $resultado = Database::ejecutar($sql, [
                htmlspecialchars($nombre_usuario),
                $password_hash,
                htmlspecialchars($nombre_completo),
                $rol
            ]);
            
            Database::registrarLog(
                'CREAR_USUARIO',
                "Nuevo usuario registrado: {$nombre_usuario}",
                null,
                'usuarios',
                $resultado['ultimo_id']
            );
            
            return [
                'exito' => true,
                'mensaje' => 'Usuario registrado exitosamente',
                'id_usuario' => $resultado['ultimo_id']
            ];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error al registrar: ' . $e->getMessage()];
        }
    }
    

    public static function login($nombre_usuario, $password) {
        try {
            $usuario = self::obtenerPorUsuario($nombre_usuario);
            
            if ($usuario && $usuario['estado'] === 'activo') {
                if (password_verify($password, $usuario['password'])) {
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                    $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
                    Database::registrarLog(
                        'LOGIN',
                        "Login exitoso: {$nombre_usuario}",
                        $usuario['id_usuario']
                    );
                    
                    return ['exito' => true, 'mensaje' => 'Bienvenido'];
                }
            }
            
            Database::registrarLog('LOGIN_FALLIDO', "Intento fallido: {$nombre_usuario}");
            return ['exito' => false, 'mensaje' => 'Usuario o contraseña incorrectos'];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error en login: ' . $e->getMessage()];
        }
    }

    public static function logout() {
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        $nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Desconocido';
        
        Database::registrarLog('LOGOUT', "Logout: {$nombre_usuario}", $id_usuario);
        session_destroy();
        return true;
    }
    

    public static function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
            return Database::seleccionarUno($sql, [$id]);
        } catch (Exception $e) {
            return null;
        }
    }
    
 
    public static function obtenerPorUsuario($nombre_usuario) {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
            return Database::seleccionarUno($sql, [$nombre_usuario]);
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Lista todos los usuarios activos
     */
    public static function listar($solo_activos = true) {
        try {
            $sql = "SELECT * FROM usuarios";
            if ($solo_activos) {
                $sql .= " WHERE estado = 'activo'";
            }
            $sql .= " ORDER BY nombre_completo ASC";
            
            return Database::seleccionar($sql);
        } catch (Exception $e) {
            return [];
        }
    }
    

    public static function estaAutenticado() {
        return isset($_SESSION['id_usuario']);
    }
    
 
    public static function obtenerActual() {
        if (self::estaAutenticado()) {
            return [
                'id_usuario' => $_SESSION['id_usuario'],
                'nombre_usuario' => $_SESSION['nombre_usuario'],
                'nombre_completo' => $_SESSION['nombre_completo'],
                'rol' => $_SESSION['rol']
            ];
        }
        return null;
    }
    

    public static function esAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
    }
}
?>