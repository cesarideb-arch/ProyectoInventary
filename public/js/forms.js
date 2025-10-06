// Funciones generales para todos los formularios
function toggleInputDisable(checkboxId, inputId) {
    const checkbox = document.getElementById(checkboxId);
    const input = document.getElementById(inputId);
    
    if (!checkbox || !input) return;
    
    // Estado inicial
    if (checkbox.checked) {
        if ($(input).hasClass('select2-hidden-accessible')) {
            $(input).val(null).trigger('change');
        } else {
            input.value = '';
        }
        input.disabled = true;
        input.removeAttribute('required');
    }
    
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            if ($(input).hasClass('select2-hidden-accessible')) {
                $(input).val(null).trigger('change');
            } else {
                input.value = '';
            }
            input.disabled = true;
            input.removeAttribute('required');
            input.classList.remove('is-invalid');
        } else {
            input.disabled = false;
            if (input.hasAttribute('data-required')) {
                input.setAttribute('required', 'required');
            }
        }
    });
}

function updateCharCounter(textarea) {
    const charCount = textarea.value.length;
    const maxLength = textarea.getAttribute('maxlength') || 500;
    const counter = textarea.nextElementSibling;
    
    if (counter && counter.classList.contains('char-counter')) {
        counter.textContent = charCount + '/' + maxLength + ' caracteres';
        
        if (charCount > maxLength) {
            counter.classList.add('text-danger');
            counter.classList.remove('text-muted');
        } else {
            counter.classList.remove('text-danger');
            counter.classList.add('text-muted');
        }
    }
}

function formatNumber(value) {
    value = value.replace(/,/g, '');
    if (!isNaN(value) && value !== '') {
        return parseInt(value).toLocaleString();
    }
    return value;
}

function unformatNumber(value) {
    return value.replace(/,/g, '');
}

function formatPrice(value) {
    value = value.replace(/,/g, '');
    if (!isNaN(value) && value !== '') {
        const parts = value.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return parts.join('.');
    }
    return value;
}

function unformatPrice(value) {
    return value.replace(/,/g, '');
}

function previewImage(event, previewId = 'imagePreview') {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(previewId);
        if (output) {
            output.src = reader.result;
            output.style.display = 'block';
        }
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

function togglePasswordVisibility(fieldId, toggleElement) {
    const passwordField = document.getElementById(fieldId);
    if (!passwordField) return;
    
    const passwordFieldType = passwordField.getAttribute('type');
    const toggleIcon = toggleElement.querySelector('i');
    
    if (passwordFieldType === 'password') {
        passwordField.setAttribute('type', 'text');
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    } else {
        passwordField.setAttribute('type', 'password');
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    }
}

// Inicialización de contadores de caracteres
function initializeCharCounters() {
    document.querySelectorAll('.form-control').forEach(input => {
        // Inicializar contador de caracteres para textareas
        if (input.hasAttribute('maxlength') && (input.tagName === 'TEXTAREA' || input.type === 'text')) {
            updateCharCounter(input);
        }
    });
}

// Inicialización de formateo de números
function initializeNumberFormatting() {
    const quantityInput = document.getElementById('quantity');
    const formattedQuantityInput = document.getElementById('formattedQuantity');
    if (quantityInput && formattedQuantityInput) {
        const initialValue = quantityInput.value;
        formattedQuantityInput.value = formatNumber(initialValue);
        
        formattedQuantityInput.addEventListener('input', function (e) {
            const formattedValue = formatNumber(e.target.value);
            e.target.value = formattedValue;
            document.getElementById('quantity').value = unformatNumber(formattedValue);
        });
    }

    const priceInput = document.getElementById('price');
    const formattedPriceInput = document.getElementById('formattedPrice');
    if (priceInput && formattedPriceInput) {
        const initialPriceValue = priceInput.value;
        formattedPriceInput.value = formatPrice(initialPriceValue);
        
        formattedPriceInput.addEventListener('input', function (e) {
            const formattedValue = formatPrice(e.target.value);
            e.target.value = formattedValue;
            document.getElementById('price').value = unformatPrice(formattedValue);
        });
    }
}

// Validación de formularios
function initializeFormValidation() {
    const forms = document.querySelectorAll('form[novalidate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let allValid = true;
            
            // Validar campos requeridos
            const requiredInputs = form.querySelectorAll('[required]');
            requiredInputs.forEach(input => {
                if (!input.disabled && !input.value.trim()) {
                    input.classList.add('is-invalid');
                    allValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Validar emails
            const emailInputs = form.querySelectorAll('input[type="email"]');
            emailInputs.forEach(input => {
                if (!input.disabled && input.value && !isValidEmail(input.value)) {
                    input.classList.add('is-invalid');
                    allValid = false;
                }
            });
            
            if (!allValid) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            this.classList.add('was-validated');
        });
    });
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Inicialización general cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    initializeCharCounters();
    initializeNumberFormatting();
    initializeFormValidation();
    
    // Inicializar toggles de checkboxes
    const toggleConfigs = [
        ['noSupplierCheck', 'supplier_id'],
        ['noMeasurementUnitCheck', 'measurement_unit'],
        ['noModelCheck', 'model'],
        ['noSerieCheck', 'serie'],
        ['noObservationsCheck', 'observations'],
        ['noRfcCheck', 'rfc'],
        ['noEmailCheck', 'email'],
        ['noAddressCheck', 'address']
    ];
    
    toggleConfigs.forEach(config => {
        toggleInputDisable(config[0], config[1]);
    });
    
    // Evento para el botón "Crear y Agregar Salida"
    const btnCrearYAgregarSalida = document.getElementById('btnCrearYAgregarSalida');
    if (btnCrearYAgregarSalida) {
        btnCrearYAgregarSalida.addEventListener('click', function() {
            document.getElementById('redirect_to_output').value = '1';
        });
    }

    // Event listeners para validación en tiempo real
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function () {
            if (this.value !== '' && this.value.length <= (this.maxLength || Infinity)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
            
            // Contador de caracteres para campos con maxlength
            if (this.hasAttribute('maxlength')) {
                updateCharCounter(this);
            }
        });
    });
    
    // Inicializar vista previa de imagen
    const imageInput = document.getElementById('profile_image');
    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            previewImage(event);
        });
    }
});