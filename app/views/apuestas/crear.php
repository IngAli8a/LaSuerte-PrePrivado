<div class="container">
    <div class="page-header">
        <div>
            <h2> Registrar Nueva Apuesta</h2>
            <p class="breadcrumb">
                <a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a> / 
                <a href="<?php echo BASE_URL; ?>apuesta/listar">Apuestas</a> / 
                <strong>Nueva Apuesta</strong>
            </p>
        </div>
    </div>
    
    <?php if ($mensaje): ?>
    <div class="alert alert-<?php echo htmlspecialchars($mensaje['tipo']); ?>">
        <?php echo htmlspecialchars($mensaje['texto']); ?>
    </div>
    <?php endif; ?>
    
    <div class="apuesta-container">
        <!-- Sección 1: Buscar Cliente -->
        <div class="seccion-card">
            <div class="seccion-header">
                <h3>Paso 1: Buscar Cliente</h3>
            </div>
            <div class="seccion-body">
                <div class="search-cliente">
                    <label for="buscar_dpi">DPI del Cliente:</label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            id="buscar_dpi" 
                            placeholder="Ingrese el DPI del cliente..."
                            class="form-control"
                            maxlength="20"
                        >
                        <button type="button" onclick="buscarCliente()" class="btn btn-primary">
                            Buscar
                        </button>
                    </div>
                    <p class="help-text">O <a href="<?php echo BASE_URL; ?>cliente/crear">registrar nuevo cliente</a></p>
                </div>
                
                <!-- Información del Cliente -->
                <div id="cliente-info" class="cliente-encontrado" style="display: none;">
                    <div class="cliente-card">
                        <div class="cliente-avatar">
                            <span id="cliente-iniciales"></span>
                        </div>
                        <div class="cliente-datos">
                            <h4 id="cliente-nombre"></h4>
                            <p id="cliente-detalles"></p>
                            <span id="cliente-cumpleanos" class="badge-cumpleanos" style="display: none;">
                                ¡Hoy es su cumpleaños! +10% en premio
                            </span>
                        </div>
                        <button type="button" onclick="limpiarCliente()" class="btn-cambiar">
                            Cambiar Cliente
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulario de Apuesta -->
        <form method="POST" action="<?php echo BASE_URL; ?>apuesta/procesarCrear" id="formApuesta" style="display: none;">
            <input type="hidden" name="id_cliente" id="id_cliente">
            <input type="hidden" name="es_cumpleanos" id="es_cumpleanos" value="0">
            
            <!-- Sección 2: Seleccionar Sorteo -->
            <div class="seccion-card">
                <div class="seccion-header">
                    <h3>Paso 2: Seleccionar Sorteo</h3>
                </div>
                <div class="seccion-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="id_tipo_sorteo">Tipo de Sorteo: <span class="requerido">*</span></label>
                            <select 
                                id="id_tipo_sorteo" 
                                name="id_tipo_sorteo" 
                                class="form-control"
                                required
                                onchange="cargarSorteos()"
                            >
                                <option value="">Seleccione un tipo de sorteo...</option>
                                <?php foreach ($tipos_sorteo as $tipo): ?>
                                <option 
                                    value="<?php echo $tipo['id_tipo_sorteo']; ?>"
                                    data-premio="<?php echo $tipo['premio_por_quetzal']; ?>"
                                >
                                    <?php echo e($tipo['nombre']); ?> 
                                    (Premio: Q<?php echo number_format($tipo['premio_por_quetzal'], 2); ?> por Q1)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_sorteo">Sorteo del Día: <span class="requerido">*</span></label>
                            <select 
                                id="id_sorteo" 
                                name="id_sorteo" 
                                class="form-control"
                                required
                                disabled
                            >
                                <option value="">Primero seleccione tipo de sorteo...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección 3: Datos de la Apuesta -->
            <div class="seccion-card">
                <div class="seccion-header">
                    <h3>Paso 3: Datos de la Apuesta</h3>
                </div>
                <div class="seccion-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="numero_apostado">Número Apostado (00-99): <span class="requerido">*</span></label>
                            <input 
                                type="number" 
                                id="numero_apostado" 
                                name="numero_apostado" 
                                class="form-control numero-grande"
                                min="0"
                                max="99"
                                required
                                placeholder="00"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="monto_apostado">Monto a Apostar (Q): <span class="requerido">*</span></label>
                            <input 
                                type="number" 
                                id="monto_apostado" 
                                name="monto_apostado" 
                                class="form-control"
                                min="1"
                                step="0.01"
                                required
                                placeholder="0.00"
                                oninput="calcularPremio()"
                            >
                        </div>
                    </div>
                    
                    <!-- Resumen de Premio -->
                    <div id="premio-calculado" class="premio-resumen" style="display: none;">
                        <div class="premio-card">
                            <div class="premio-icon"></div>
                            <div class="premio-info">
                                <span class="premio-label">Premio Potencial:</span>
                                <span class="premio-valor" id="premio-valor">Q 0.00</span>
                                <span class="premio-detalle" id="premio-detalle"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="form-acciones">
                <button type="submit" class="btn btn-success btn-large">
                    ✓ Registrar Apuesta
                </button>
                <a href="<?php echo BASE_URL; ?>apuesta/listar" class="btn btn-secondary">
                    ✕ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<style>
/* Contenedor de Apuesta */
.apuesta-container {
    max-width: 900px;
    margin: 0 auto;
}

/* Secciones Card */
.seccion-card {
    background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
    overflow: hidden;
}

.seccion-header {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    padding: 20px 30px;
    border-bottom: 1px solid rgba(76, 175, 80, 0.2);
}

.seccion-header h3 {
    margin: 0;
    color: #1b5e20;
    font-size: 18px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.seccion-body {
    padding: 30px;
}

/* Búsqueda de Cliente */
.search-cliente label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #2d3748;
}

.input-group {
    display: flex;
    gap: 10px;
}

.input-group .form-control {
    flex: 1;
}

.help-text {
    margin-top: 10px;
    font-size: 13px;
    color: #718096;
}

.help-text a {
    color: #4caf50;
    text-decoration: none;
    font-weight: 600;
}

.help-text a:hover {
    text-decoration: underline;
}

/* Cliente Encontrado */
.cliente-encontrado {
    margin-top: 25px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.cliente-card {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    padding: 25px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 20px;
    border: 2px solid #4caf50;
}

.cliente-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.cliente-datos {
    flex: 1;
}

.cliente-datos h4 {
    margin: 0 0 5px 0;
    color: #1b5e20;
    font-size: 20px;
    font-weight: 700;
}

.cliente-datos p {
    margin: 0;
    color: #558b2f;
    font-size: 14px;
}

.badge-cumpleanos {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    color: #e65100;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(255, 152, 0, 0.2);
}

.btn-cambiar {
    padding: 10px 20px;
    background: white;
    border: 2px solid #4caf50;
    color: #4caf50;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cambiar:hover {
    background: #4caf50;
    color: white;
}

/* Formulario */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
}

.requerido {
    color: #e53e3e;
}

.form-control {
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #4caf50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

.numero-grande {
    font-size: 32px;
    font-weight: 700;
    text-align: center;
    color: #2e7d32;
}

/* Premio Calculado */
.premio-resumen {
    margin-top: 25px;
    animation: slideDown 0.3s ease;
}

.premio-card {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    padding: 25px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 20px;
    border: 2px solid #ff9800;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
}

.premio-icon {
    font-size: 48px;
}

.premio-info {
    flex: 1;
}

.premio-label {
    display: block;
    font-size: 14px;
    color: #e65100;
    font-weight: 600;
    margin-bottom: 5px;
}

.premio-valor {
    display: block;
    font-size: 36px;
    font-weight: 800;
    color: #e65100;
    line-height: 1;
    margin-bottom: 5px;
}

.premio-detalle {
    display: block;
    font-size: 13px;
    color: #f57c00;
}

/* Botones de Acción */
.form-acciones {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn-large {
    padding: 16px 40px;
    font-size: 16px;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}


@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .cliente-card {
        flex-direction: column;
        text-align: center;
    }
    
    .premio-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
let clienteSeleccionado = null;
let premioMultiplicador = 0;

// Buscar cliente por DPI
async function buscarCliente() {
    const dpi = document.getElementById('buscar_dpi').value.trim();
    
    if (!dpi) {
        alert('Por favor ingrese el DPI del cliente');
        return;
    }
    
    try {
        const response = await fetch('<?php echo BASE_URL; ?>cliente/buscarPorDPI?dpi=' + encodeURIComponent(dpi));
        const data = await response.json();
        
        if (data.exito) {
            clienteSeleccionado = data.cliente;
            mostrarCliente(data.cliente);
            document.getElementById('formApuesta').style.display = 'block';
        } else {
            alert('Cliente no encontrado. ¿Desea registrarlo?');
            window.location.href = '<?php echo BASE_URL; ?>cliente/crear';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al buscar cliente');
    }
}

// Mostrar información del cliente
function mostrarCliente(cliente) {
    const iniciales = (cliente.nombre.charAt(0) + cliente.apellido.charAt(0)).toUpperCase();
    const esCumpleanos = verificarCumpleanos(cliente.fecha_nacimiento);
    
    document.getElementById('cliente-iniciales').textContent = iniciales;
    document.getElementById('cliente-nombre').textContent = `${cliente.nombre} ${cliente.apellido}`;
    document.getElementById('cliente-detalles').textContent = `DPI: ${cliente.dpi} | Tel: ${cliente.telefono}`;
    document.getElementById('cliente-info').style.display = 'block';
    document.getElementById('id_cliente').value = cliente.id_cliente;
    document.getElementById('es_cumpleanos').value = esCumpleanos ? '1' : '0';
    
    if (esCumpleanos) {
        document.getElementById('cliente-cumpleanos').style.display = 'inline-block';
    }

    document.querySelector('.search-cliente').style.display = 'none';
}

// Verificar si es cumpleaños
function verificarCumpleanos(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    return (hoy.getMonth() === nacimiento.getMonth() && hoy.getDate() === nacimiento.getDate());
}

// Limpiar cliente seleccionado
function limpiarCliente() {
    clienteSeleccionado = null;
    document.getElementById('cliente-info').style.display = 'none';
    document.querySelector('.search-cliente').style.display = 'block';
    document.getElementById('buscar_dpi').value = '';
    document.getElementById('formApuesta').style.display = 'none';
    document.getElementById('formApuesta').reset();
}

// Cargar sorteos según tipo seleccionado
async function cargarSorteos() {
    const tipoSelect = document.getElementById('id_tipo_sorteo');
    const sorteoSelect = document.getElementById('id_sorteo');
    const tipoId = tipoSelect.value;
    
    // Guardar multiplicador de premio
    const selectedOption = tipoSelect.options[tipoSelect.selectedIndex];
    premioMultiplicador = parseFloat(selectedOption.dataset.premio) || 0;
    
    if (!tipoId) {
        sorteoSelect.innerHTML = '<option value="">Primero seleccione tipo de sorteo...</option>';
        sorteoSelect.disabled = true;
        return;
    }
    
    try {
        const response = await fetch('<?php echo BASE_URL; ?>sorteo/obtenerSorteosActivos?tipo_id=' + tipoId);
        const data = await response.json();
        
        if (data.exito && data.sorteos.length > 0) {
            sorteoSelect.innerHTML = '<option value="">Seleccione un sorteo...</option>';
            data.sorteos.forEach(sorteo => {
                const option = document.createElement('option');
                option.value = sorteo.id_sorteo;
                option.textContent = `Sorteo #${sorteo.numero_sorteo_dia} - ${sorteo.hora_sorteo || 'Hoy'}`;
                sorteoSelect.appendChild(option);
            });
            sorteoSelect.disabled = false;
        } else {
            sorteoSelect.innerHTML = '<option value="">No hay sorteos disponibles hoy</option>';
            sorteoSelect.disabled = true;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar sorteos');
    }
}

// Calcular premio potencial
function calcularPremio() {
    const monto = parseFloat(document.getElementById('monto_apostado').value) || 0;
    const esCumpleanos = document.getElementById('es_cumpleanos').value === '1';
    
    if (monto > 0 && premioMultiplicador > 0) {
        let premio = monto * premioMultiplicador;
        let bonoCumpleanos = 0;
        
        if (esCumpleanos) {
            bonoCumpleanos = premio * 0.10;
            premio += bonoCumpleanos;
        }
        
        document.getElementById('premio-valor').textContent = 'Q ' + premio.toFixed(2);
        
        let detalle = `Apuesta: Q${monto.toFixed(2)} × ${premioMultiplicador}`;
        if (esCumpleanos) {
            detalle += ` + Bono cumpleaños (10%): Q${bonoCumpleanos.toFixed(2)}`;
        }
        document.getElementById('premio-detalle').textContent = detalle;
        document.getElementById('premio-calculado').style.display = 'block';
    } else {
        document.getElementById('premio-calculado').style.display = 'none';
    }
}

// Validación del formulario
document.getElementById('formApuesta')?.addEventListener('submit', function(e) {
    const numero = parseInt(document.getElementById('numero_apostado').value);
    
    if (numero < 0 || numero > 99) {
        e.preventDefault();
        alert('El número debe estar entre 00 y 99');
        return false;
    }
});
</script>