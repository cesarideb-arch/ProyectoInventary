// resources/js/products.js
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de SweetAlert para mensajes del sistema
    const setupSweetAlerts = () => {
        // Mostrar mensajes de éxito
        if (typeof successMessage !== 'undefined' && successMessage) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: successMessage,
                background: '#f7fafc',
                confirmButtonColor: '#000000ff'
            });
        }

        // Mostrar mensajes de error
        if (typeof errorMessage !== 'undefined' && errorMessage) {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: errorMessage,
                background: '#f7fafc',
                confirmButtonColor: '#000000ff'
            });
        }
    };

    // Manejo de formularios de eliminación
    const setupDeleteForms = () => {
        const deleteForms = document.querySelectorAll('.products-delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres eliminar este producto?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000ff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    background: '#f7fafc'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    };

    // Validación del campo de salto de página
    const setupPageInputValidation = () => {
        const pageInputs = document.querySelectorAll('.products-page-input');
        
        pageInputs.forEach(input => {
            input.addEventListener('change', function() {
                const maxPage = parseInt(this.getAttribute('max')) || 1;
                const minPage = parseInt(this.getAttribute('min')) || 1;
                
                if (this.value > maxPage) {
                    this.value = maxPage;
                } else if (this.value < minPage) {
                    this.value = minPage;
                }
            });
        });
    };

    // Toggle para mostrar/ocultar detalles
    const setupDetailsToggle = () => {
        document.querySelectorAll('.products-toggle-details').forEach(element => {
            element.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const detailsRow = document.getElementById('products-details-' + productId);
                const icon = this.querySelector('i');
                
                if (detailsRow.classList.contains('show')) {
                    detailsRow.classList.remove('show');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    detailsRow.classList.add('show');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });
    };

    // Función para manejar la descarga de PDF con filtros
    const setupPdfDownload = () => {
        // Esta función asegura que los parámetros de búsqueda se mantengan en la descarga
        const pdfButtons = document.querySelectorAll('a[href*="download=pdf"], a[href*="download=excel"]');
        
        pdfButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Obtener los parámetros actuales de la URL
                const currentUrl = new URL(window.location.href);
                const currentParams = new URLSearchParams(currentUrl.search);
                
                // Obtener la URL del botón
                const buttonUrl = new URL(this.href);
                const buttonParams = new URLSearchParams(buttonUrl.search);
                
                // Combinar parámetros (mantener query y page si existen)
                if (currentParams.has('query')) {
                    buttonParams.set('query', currentParams.get('query'));
                }
                if (currentParams.has('page')) {
                    buttonParams.set('page', currentParams.get('page'));
                }
                
                // Actualizar la URL del botón
                buttonUrl.search = buttonParams.toString();
                this.href = buttonUrl.toString();
            });
        });
    };

    // Inicialización de todas las funcionalidades
    const initProducts = () => {
        setupSweetAlerts();
        setupDeleteForms();
        setupPageInputValidation();
        setupDetailsToggle();
        setupPdfDownload();
    };

    // Inicializar cuando el DOM esté listo
    initProducts();
});