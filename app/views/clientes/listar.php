<div class="container">
    <div class="page-header">
        <div>
            <h2>Lista de Clientes</h2>
            <p class="breadcrumb">
                <a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a> / 
                <strong>Clientes</strong>
            </p>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>cliente/crear" class="btn btn-success">
                ➕ Nuevo Cliente
            </a>
        </div>
    </div>
    
    <?php if ($mensaje): ?>
    <div class="alert alert-<?php echo htmlspecialchars($mensaje['tipo']); ?>">
        <?php echo htmlspecialchars($mensaje['texto']); ?>
    </div>
    <?php endif; ?>
    
    <!-- Buscador y Filtros -->
    <div class="filtros-container">
        <div class="search-box">
            <input 
                type="text" 
                id="buscarCliente" 
                placeholder=" Buscar por nombre, apellido, DPI o teléfono..." 
                class="search-input"
            >
        </div>
        <div class="filtros-info">
            <span class="badge badge-info">
                Total: <strong><?php echo count($clientes); ?></strong> clientes
            </span>
        </div>
    </div>
    
    <?php if (!empty($clientes)): ?>
    <div class="tabla-container">
        <table class="tabla tabla-hover" id="tablaClientes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>DPI</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Fecha Nacimiento</th>
                    <th>Fecha Registro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><code>#<?php echo str_pad($cliente['id_cliente'], 4, '0', STR_PAD_LEFT); ?></code></td>
                    <td>
                        <div class="cliente-info">
                            <div class="avatar">
                                <?php echo strtoupper(substr($cliente['nombre'], 0, 1) . substr($cliente['apellido'], 0, 1)); ?>
                            </div>
                            <strong><?php echo e($cliente['nombre'] . ' ' . $cliente['apellido']); ?></strong>
                        </div>
                    </td>
                    <td><?php echo e($cliente['dpi']); ?></td>
                    <td>
                        <a href="tel:<?php echo e($cliente['telefono']); ?>" class="telefono-link">
                            <?php echo e($cliente['telefono']); ?>
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($cliente['email'])): ?>
                            <a href="mailto:<?php echo e($cliente['email']); ?>" class="email-link">
                             <?php echo e($cliente['email']); ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Sin email</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($cliente['fecha_nacimiento'])); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($cliente['fecha_registro'])); ?></td>
                    <td>
                        <?php 
                        $estado_activo = ($cliente['estado'] == 'activo');
                        ?>
                        <span class="badge badge-<?php echo $estado_activo ? 'activo' : 'inactivo'; ?>">
                            <?php echo $estado_activo ? '✓ Activo' : '✕ Inactivo'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="acciones-grupo">
                            <a href="<?php echo BASE_URL; ?>cliente/editar?id=<?php echo $cliente['id_cliente']; ?>" 
                               class="btn-icon btn-editar" 
                               title="Editar cliente">
                                
                            </a>
                            <a href="<?php echo BASE_URL; ?>apuesta/crear?cliente_id=<?php echo $cliente['id_cliente']; ?>" 
                               class="btn-icon btn-apuesta" 
                               title="Nueva apuesta">
                                
                            </a>
                            <button onclick="verHistorial(<?php echo $cliente['id_cliente']; ?>)" 
                                    class="btn-icon btn-historial" 
                                    title="Ver historial">
                                
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alerta-vacia">
        <div class="icono-vacio">👥</div>
        <p>No hay clientes registrados</p>
        <a href="<?php echo BASE_URL; ?>cliente/crear" class="btn btn-primary" style="margin-top: 20px;">
            ➕ Registrar Primer Cliente
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
/* Contenedor de Filtros */
.filtros-container {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 300px;
}

.search-input {
    width: 100%;
    padding: 12px 20px;
    border: 2px solid #e8f5e9;
    border-radius: 25px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #4caf50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

.filtros-info {
    display: flex;
    gap: 10px;
}

/* Header con botón */
.page-header {
    background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%);
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
}

.page-header h2 {
    color: white;
    margin: 0 0 5px 0;
    font-size: 28px;
    font-weight: 700;
}

.page-header .breadcrumb {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    margin: 0;
}

.page-header .breadcrumb a {
    color: white;
    text-decoration: none;
    opacity: 0.8;
}

.page-header .breadcrumb a:hover {
    opacity: 1;
    text-decoration: underline;
}

/* Cliente Info con Avatar */
.cliente-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

/* Enlaces */
.telefono-link, .email-link {
    color: #2e7d32;
    text-decoration: none;
    font-size: 13px;
}

.telefono-link:hover, .email-link:hover {
    text-decoration: underline;
    color: #1b5e20;
}

.text-muted {
    color: #999;
    font-size: 13px;
    font-style: italic;
}

/* Badges mejorados */
.badge-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.badge-activo {
    background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%);
    color: #1b5e20;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.badge-inactivo {
    background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%);
    color: #c62828;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

/* Acciones en tabla */
.acciones-grupo {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    text-decoration: none;
}

.btn-editar {
    background: linear-gradient(135deg, #fff9c4 0%, #fff59d 100%);
    border: 1px solid #fbc02d;
}

.btn-editar:hover {
    background: linear-gradient(135deg, #fff59d 0%, #ffeb3b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(251, 192, 45, 0.3);
}

.btn-apuesta {
    background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%);
    border: 1px solid #4caf50;
}

.btn-apuesta:hover {
    background: linear-gradient(135deg, #a5d6a7 0%, #81c784 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
}

.btn-historial {
    background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%);
    border: 1px solid #2196f3;
}

.btn-historial:hover {
    background: linear-gradient(135deg, #90caf9 0%, #64b5f6 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
}

/* Tabla hover mejorada */
.tabla-hover tbody tr {
    transition: all 0.2s ease;
}

.tabla-hover tbody tr:hover {
    background: #f1f8e9;
    transform: scale(1.005);
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.1);
}

/* Alerta vacía mejorada */
.alerta-vacia {
    text-align: center;
    padding: 80px 40px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 2px dashed #c8e6c9;
}

.icono-vacio {
    font-size: 72px;
    margin-bottom: 20px;
    opacity: 0.6;
}

.alerta-vacia p {
    font-size: 20px;
    color: #558b2f;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .filtros-container {
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
        min-width: 100%;
    }
    
    .tabla-container {
        overflow-x: auto;
    }
    
    .cliente-info {
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }
    
    .acciones-grupo {
        flex-direction: column;
    }
}
</style>

<script>
// Búsqueda en tiempo real
document.getElementById('buscarCliente').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('tablaClientes');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

// Ver historial del cliente
function verHistorial(clienteId) {
    window.location.href = '<?php echo BASE_URL; ?>apuesta/historial?cliente_id=' + clienteId;
}
</script>