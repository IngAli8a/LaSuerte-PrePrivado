<div class="container">
    <div class="page-header">
        <h2>Dashboard</h2>
        <div>
            <span style="color: #558b2f; font-size: 14px;">
                <?php echo e($usuario['nombre_completo']); ?> 
                (<?php echo e($usuario['rol']); ?>)
            </span>
        </div>
    </div>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?php echo e($error); ?>
    </div>
    <?php endif; ?>
    
    
    <div class="acciones-rapidas-dashboard">

    <a href="<?php echo BASE_URL; ?>cliente/crear" class="accion-card">
        <div class="accion-icon"><i class="fa-regular fa-user"></i></div>
        <span>Ingresar Cliente</span>
    </a>

    <a href="<?php echo BASE_URL; ?>apuesta/crear" class="accion-card">
        <div class="accion-icon"><i class="fa-solid fa-cash-register"></i></div>
        <span>Registrar Apuesta</span>
    </a>

    <a href="<?php echo BASE_URL; ?>apuesta/listar" class="accion-card">
        <div class="accion-icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <span>Ver Apuestas</span>
    </a>

    <a href="<?php echo BASE_URL; ?>cliente/listar" class="accion-card">
        <div class="accion-icon"><i class="fa-regular fa-address-card"></i></div>
        <span>Lista de Clientes</span>
    </a>

    <a href="<?php echo BASE_URL; ?>reporte/recaudacion" class="accion-card">
        <div class="accion-icon"><i class="fa-regular fa-money-bill-1"></i></div>
        <span>Recaudación</span>
    </a>

</div>


</div>

<style>

.acciones-rapidas-dashboard {
    margin: 35px 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 25px;
}

.accion-card {
    background: #ffffff;
    padding: 26px;
    border-radius: 18px;
    text-decoration: none;
    color: #1f4d2c;
    font-weight: 700;
    font-size: 17px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    text-align: center;
    border: 1px solid #e4ede8;
    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
    transition: 0.28s ease;
    position: relative;
    overflow: hidden;
}

.accion-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(45, 106, 59, 0.20);
    border-color: #2d6a3b;
}

.accion-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    background: linear-gradient(135deg, #2d6a3b, #3e8850);
    box-shadow: 0 5px 15px rgba(45,106,59,0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    margin-bottom: 8px;
}

/* efecto decorativo */
.accion-card::before {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(45,106,59,0.12) 0%, transparent 70%);
    border-radius: 50%;
    transition: 0.4s;
}

.accion-card:hover::before {
    transform: scale(1.15);
}


.stat-label {
    font-size: 14px;
    color: #486754;
    font-weight: 500;
    margin-top: 3px;
}

.acciones-rapidas {
    margin: 30px 0;
    padding: 20px;
    background-color: #f1f8e9;
    border-radius: 12px;

}

.acciones-rapidas h3 {
    color: #1b5e20;
    margin-bottom: 15px;
}

.botones-grupo {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
}

.botones-grupo .btn {
    text-align: center;
}

.ultimas-apuestas {
    margin-top: 30px;
}

.ultimas-apuestas h3 {
    color: #1b5e20;
    margin-bottom: 15px;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.badge-registrada {
    background-color: #e3f2fd;
    color: #1565c0;
}

.badge-ganadora {
    background-color: #c8e6c9;
    color: #1b5e20;
}

.badge-perdedora {
    background-color: #ffccbc;
    color: #bf360c;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .botones-grupo {
        grid-template-columns: 1fr;
    }
}

body {
    background: var(--bg-main);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ===== HEADER MEJORADO ===== */
.page-header {
    background:#fff;
    padding: 30px 35px;

    margin-bottom: 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;

}

.page-header h2 {
    color: var(--text-dark);
    margin: 0;
    font-size: 32px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-header .user-info {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    padding: 12px 24px;
    border-radius: 30px;
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
    border: 1px solid rgba(76, 175, 80, 0.2);
}

.page-header .user-info span {
    color: var(--primary-dark);
    font-size: 15px;
    font-weight: 600;
}


.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
    margin: 35px 0;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    border-radius: 24px;
    padding: 28px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(76, 175, 80, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(40px, -40px);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 40px rgba(76, 175, 80, 0.15);
    border-color: rgba(76, 175, 80, 0.2);
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.stat-icon-container {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    position: relative;
    z-index: 1;
}

/* Iconos con colores específicos */
.stat-card:nth-child(1) .stat-icon-container {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
}

.stat-card:nth-child(2) .stat-icon-container {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    box-shadow: 0 4px 12px rgba(156, 39, 176, 0.2);
}

.stat-card:nth-child(3) .stat-icon-container {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
}

.stat-card:nth-child(4) .stat-icon-container {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    box-shadow: 0 4px 12px rgba(255, 152, 0, 0.2);
}

.stat-change {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
}

.stat-change.positive {
    background: rgba(76, 175, 80, 0.1);
    color: #2e7d32;
}

.stat-change.negative {
    background: rgba(244, 67, 54, 0.1);
    color: #c62828;
}

.stat-content {
    position: relative;
    z-index: 1;
}

.stat-label {
    font-size: 13px;
    color: var(--text-light);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.stat-valor {
    font-size: 36px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 8px;
}

.stat-description {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>