// resources/js/users.js
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

    // Manejo de botones de eliminación
    const setupDeleteButtons = () => {
        const deleteButtons = document.querySelectorAll('.users-delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const form = document.getElementById('users-delete-form');
                form.action = `/users/${userId}`;
                $('#users-delete-modal').modal('show');
            });
        });
    };

    // Manejo del formulario de eliminación
    const setupDeleteForm = () => {
        const form = document.getElementById('users-delete-form');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Usuario eliminado con éxito') {
                        $('#users-delete-modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            background: '#f7fafc',
                            confirmButtonColor: '#000000ff'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: data.message,
                            background: '#f7fafc',
                            confirmButtonColor: '#000000ff'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Error al eliminar el usuario. Por favor, inténtalo de nuevo más tarde.',
                        background: '#f7fafc',
                        confirmButtonColor: '#000000ff'
                    });
                });
            });
        }
    };

    // Validación del campo de salto de página
    const setupPageInputValidation = () => {
        const pageInputs = document.querySelectorAll('.users-page-input');
        
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

    // Función para mostrar/ocultar contraseña
    const setupPasswordToggle = () => {
        window.togglePasswordVisibility = function(fieldId, toggleButton) {
            var passwordField = document.getElementById(fieldId);
            var passwordFieldType = passwordField.getAttribute('type');
            var toggleIcon = toggleButton.querySelector('i');
            if (passwordFieldType === 'password') {
                passwordField.setAttribute('type', 'text');
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordField.setAttribute('type', 'password');
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        };
    };

    // Inicialización de todas las funcionalidades
    const initUsers = () => {
        setupSweetAlerts();
        setupDeleteButtons();
        setupDeleteForm();
        setupPageInputValidation();
        setupPasswordToggle();
    };

    // Inicializar cuando el DOM esté listo
    initUsers();
});