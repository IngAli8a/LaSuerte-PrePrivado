<div class="login-wrapper">

  
    <div class="login-icon">
        <i class="fa-solid fa-user"></i>
    </div>

    <div class="login-card">

        <h2 class="login-title">Inicio de Sesión</h2>

        <p class="login-subtitle">Sistema de Gestión de Loterías "La Suerte"</p>

        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo e($mensaje['tipo']); ?>">
            <?php echo e($mensaje['texto']); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo BASE_URL; ?>auth/procesarLogin">

            <div class="input-group">
                <i class="fa-solid fa-user icon-input"></i>
                <input 
                    type="text" 
                    id="nombre_usuario" 
                    name="nombre_usuario" 
                    placeholder="Usuario"
                    required
                >
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock icon-input"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Contraseña"
                    required
                >
            </div>

            <button type="submit" class="btn-login">Inicio</button>

            <div class="opciones-extra">
                <label><input type="checkbox"> Recordar</label>
                <a href="#">Olvidaste tu contraseña?</a>
            </div>

        </form>

        <div class="login-footer">
            <p>No tienes una cuenta? <a href="<?php echo BASE_URL; ?>auth/registro">Registrarse aqui</a></p>
        </div>

    </div>
</div>
