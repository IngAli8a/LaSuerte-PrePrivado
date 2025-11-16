// ===== MENÚ MÓVIL =====
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navbarMenu = document.getElementById('navbarMenu');
    
    // Toggle menú hamburguesa
    if (hamburger && navbarMenu) {
        hamburger.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
        
        // Cerrar menú al hacer click en un link
        const navLinks = navbarMenu.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // En mobile, cerrar el menú después de hacer click
                if (window.innerWidth <= 768) {
                    navbarMenu.classList.remove('active');
                    hamburger.classList.remove('active');
                }
            });
        });
    }
    
    // Manejar redimensionamiento
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            if (navbarMenu) navbarMenu.classList.remove('active');
            if (hamburger) hamburger.classList.remove('active');
        }
    });
});

/**
 * Formatea una fecha
 */
function formatearFecha(fecha) {
    const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

/**
 * Muestra un mensaje flotante
 */
function mostrarMensaje(texto, tipo = 'info', duracion = 3000) {
    const mensaje = document.createElement('div');
    mensaje.className = `mensaje-flotante mensaje-${tipo}`;
    mensaje.textContent = texto;
    
    document.body.appendChild(mensaje);
    
    setTimeout(() => {
        mensaje.remove();
    }, duracion);
}

/**
 * Confirmar acción
 */
function confirmar(mensaje) {
    return confirm(mensaje);
}

/**
 * Hacer solicitud AJAX
 */
function solicitud(url, opciones = {}) {
    const defecto = {
        metodo: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    const config = { ...defecto, ...opciones };
    config.method = config.metodo;
    delete config.metodo;
    
    return fetch(url, config)
        .then(res => res.json())
        .catch(err => {
            console.error('Error en solicitud:', err);
            mostrarMensaje('Error en la solicitud', 'error');
            throw err;
        });
}

// ===== MANEJADORES DE EVENTOS =====

document.addEventListener('DOMContentLoaded', function() {
    // Cerrar alertas al hacer clic
    const alertas = document.querySelectorAll('.alert');
    alertas.forEach(alert => {
        alert.addEventListener('click', function() {
            // Permitir cerrar la alerta después de 5 segundos
            setTimeout(() => {
                this.style.display = 'none';
            }, 5000);
        });
    });
    
    // Validar formularios antes de enviar
    const formularios = document.querySelectorAll('.formulario');
    formularios.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('[required]');
            let valido = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    valido = false;
                } else {
                    input.classList.remove('error');
                }
            });
            
            if (!valido) {
                e.preventDefault();
                mostrarMensaje('Por favor completa todos los campos requeridos', 'warning');
            }
        });
        
        // Quitar clase de error al escribir
        const inputs = form.querySelectorAll('[required]');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
    });
});



const style = document.createElement('style');
style.textContent = `
    .form-control.error {
        border-color: #dc3545 !important;
        background-color: #fff5f5;
    }
    
    .mensaje-flotante {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        color: white;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
    }
    
    .mensaje-info {
        background-color: #17a2b8;
    }
    
    .mensaje-success {
        background-color: #28a745;
    }
    
    .mensaje-warning {
        background-color: #ffc107;
        color: #333;
    }
    
    .mensaje-error {
        background-color: #dc3545;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);