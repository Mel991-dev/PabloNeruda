/* JavaScript personalizado para el sistema */

// Función para mostrar alertas toast
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.role = 'alert';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.style.position = 'fixed';
    container.style.top = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Formatear números a decimal (para notas)
function formatDecimal(number, decimals = 2) {
    return parseFloat(number).toFixed(decimals);
}

// Calcular promedio de notas
function calcularPromedio(notas) {
    const notasValidas = notas.filter(n => n !== '' && !isNaN(n));
    if (notasValidas.length === 0) return 0;
    const suma = notasValidas.reduce((acc, n) => acc + parseFloat(n), 0);
    return formatDecimal(suma / notasValidas.length, 2);
}

// Determinar estado (Aprobado/Reprobado)
function determinarEstado(promedio) {
    return parseFloat(promedio) >= 3.0 ? 'Aprobado' : 'Reprobado';
}

// Validar rango de nota (0.0 - 5.0)
function validarNota(valor) {
    const nota = parseFloat(valor);
    return !isNaN(nota) && nota >= 0 && nota <= 5.0;
}

// Confirmar eliminación
function confirmarEliminacion(mensaje = '¿Estás seguro de que deseas eliminar este elemento?') {
    return confirm(mensaje);
}

// Auto-ocultamiento de mensajes flash
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Barra lateral dinámica: Pin con clic
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.addEventListener('click', function (e) {
            // Evitar que el clic en un enlace active el pin si solo se quiere navegar
            if (e.target.closest('a')) return;

            this.classList.toggle('pinned');
            localStorage.setItem('sidebarPinned', this.classList.contains('pinned'));
        });

        // Recuperar estado previo
        if (localStorage.getItem('sidebarPinned') === 'true') {
            sidebar.classList.add('pinned');
        }
    }
});

// Validación de formularios Bootstrap
(function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();
