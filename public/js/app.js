// JavaScript para toggle del sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContainer = document.getElementById('mainContainer');
    const footer = document.getElementById('footer');
    const toggleBtn = document.getElementById('toggleSidebar');
    const mobileToggle = document.getElementById('mobile-toggle');
    
    // Verificar que los elementos existen
    if (!sidebar || !mainContainer || !footer || !toggleBtn || !mobileToggle) {
        console.error('No se encontraron todos los elementos necesarios');
        return;
    }

    const toggleIcon = toggleBtn.querySelector('i');

    // Función para toggle del sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContainer.classList.toggle('collapsed');
        footer.classList.toggle('collapsed');
        
        // Cambiar el ícono según el estado
        if (sidebar.classList.contains('collapsed')) {
            toggleIcon.className = 'fas fa-angle-double-right';
        } else {
            toggleIcon.className = 'fas fa-angle-double-left';
        }
        
        // Guardar el estado en localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    // Función para toggle móvil
    function toggleMobileSidebar() {
        sidebar.classList.toggle('active');
        
        // Agregar overlay cuando el sidebar está activo en móviles
        if (sidebar.classList.contains('active')) {
            createOverlay();
        } else {
            removeOverlay();
        }
    }

    // Crear overlay para móviles
    function createOverlay() {
        if (document.getElementById('sidebar-overlay')) return;
        
        const overlay = document.createElement('div');
        overlay.id = 'sidebar-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 998;
        `;
        overlay.addEventListener('click', toggleMobileSidebar);
        document.body.appendChild(overlay);
    }

    // Remover overlay
    function removeOverlay() {
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) {
            overlay.remove();
        }
    }

    // Cargar estado guardado
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        mainContainer.classList.add('collapsed');
        footer.classList.add('collapsed');
        toggleIcon.className = 'fas fa-angle-double-right';
    }

    // Event listeners
    toggleBtn.addEventListener('click', toggleSidebar);
    mobileToggle.addEventListener('click', toggleMobileSidebar);

    // Cerrar sidebar en móvil al hacer clic en un enlace
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a, .sidebar-menu button');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                sidebar.classList.remove('active');
                removeOverlay();
            }
        });
    });

    // Manejar redimensionamiento de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('active');
            removeOverlay();
        }
    });

    // Inicializar tooltips de Bootstrap si están disponibles
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
});

// Funciones globales para SweetAlert
function confirmAction(message, callback) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: message,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        timer: 5000,
        confirmButtonText: 'Entendido'
    });
}

// Función para mostrar loading
function showLoading(message = 'Procesando...') {
    Swal.fire({
        title: message,
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Función para cerrar loading
function hideLoading() {
    Swal.close();
}