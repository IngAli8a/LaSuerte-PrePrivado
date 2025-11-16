<div class="auth-container">
    <div class="auth-box auth-box-registro">
        <div class="auth-header">
            <h2> Crear Nueva Cuenta</h2>
            <p class="auth-subtitle">Sistema de Gestión de Loterías "La Suerte"</p>
        </div>
        
        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo e($mensaje['tipo']); ?>">
            <?php echo e($mensaje['texto']); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>auth/procesarRegistro" class="auth-form">
            <div class="form-group">
                <label for="nombre_completo">Nombre Completo: <span class="requerido">*</span></label>
                <input 
                    type="text" 
                    id="nombre_completo" 
                    name="nombre_completo" 
                    class="form-control" 
                    required 
                    autofocus
                    placeholder="Tu nombre completo"
                >
            </div>
            
            <div class="form-group">
                <label for="nombre_usuario">Usuario: <span class="requerido">*</span></label>
                <input 
                    type="text" 
                    id="nombre_usuario" 
                    name="nombre_usuario" 
                    class="form-control" 
                    required
                    minlength="4"
                    placeholder="Nombre de usuario único (mínimo 4 caracteres)"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña: <span class="requerido">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    required
                    minlength="6"
                    placeholder="Mínimo 6 caracteres"
                >
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Confirmar Contraseña: <span class="requerido">*</span></label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    class="form-control" 
                    required
                    minlength="6"
                    placeholder="Confirma tu contraseña"
                >
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                ✓ Crear Cuenta
            </button>
        </form>
        
        <div class="auth-footer">
            <p>¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>auth/login">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>