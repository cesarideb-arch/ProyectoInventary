// resources/js/categories.js
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
                confirmButtonColor: '#000000'
            });
        }

        // Mostrar mensajes de error
        if (typeof errorMessage !== 'undefined' && errorMessage) {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: errorMessage,
                background: '#f7fafc',
                confirmButtonColor: '#000000'
            });
        }
    };

    // Manejo de formularios de eliminación
    const setupDeleteForms = () => {
        const deleteForms = document.querySelectorAll('.categories-delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres eliminar esta categoría?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000',
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

    // Validación de entrada de página
    const setupPageInputValidation = () => {
        const pageInput = document.querySelector('.categories-page-input');
        
        if (pageInput) {
            pageInput.addEventListener('change', function() {
                const maxPage = parseInt(this.getAttribute('max')) || 1;
                const minPage = parseInt(this.getAttribute('min')) || 1;
                
                if (this.value > maxPage) {
                    this.value = maxPage;
                } else if (this.value < minPage) {
                    this.value = minPage;
                }
            });
        }
    };

    // Inicialización de todas las funcionalidades
    const initCategories = () => {
        setupSweetAlerts();
        setupDeleteForms();
        setupPageInputValidation();
    };

    // Inicializar cuando el DOM esté listo
    initCategories();
});