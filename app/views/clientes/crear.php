<div class="container">
    <div class="page-header">
        <h2>Registrar Nuevo Cliente</h2>
        <p class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a> / 
            <a href="<?php echo BASE_URL; ?>cliente/listar">Clientes</a> / 
            <strong>Nuevo Cliente</strong>
        </p>
    </div>
    
    <?php if ($mensaje): ?>
    <div class="alert alert-<?php echo htmlspecialchars($mensaje['tipo']); ?>">
        <?php echo htmlspecialchars($mensaje['texto']); ?>
    </div>
    <?php endif; ?>
    
    <div class="form-container">
        <form method="POST" action="<?php echo BASE_URL; ?>cliente/procesarCrear">
            
            <div class="input-group">
                <i class="fa-solid fa-signature icon-input"></i>
                <input type="text" name="nombre" placeholder="Nombre" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-user icon-input"></i>
                <input type="text" name="apellido" placeholder="Apellido" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-id-card icon-input"></i>
                <input type="text" name="dpi" placeholder="DPI" maxlength="20" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-phone icon-input"></i>
                <input type="tel" name="telefono" placeholder="Teléfono" maxlength="15" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-cake-candles icon-input"></i>
                <input type="date" name="fecha_nacimiento" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-envelope icon-input"></i>
                <input type="email" name="email" placeholder="Email (opcional)">
            </div>

            <div class="input-group textarea">
                <i class="fa-solid fa-location-dot icon-input"></i>
                <textarea name="direccion" placeholder="Dirección (opcional)" rows="3"></textarea>
            </div>

            <button type="submit" class="btn-registrar">Registrar</button>

            <a href="<?php echo BASE_URL; ?>cliente/listar" class="btn-cancelar">Cancelar</a>

        </form>
    </div>
</div>