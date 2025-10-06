// resources/js/entrances.js
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización del datepicker
    const initializeDatepicker = () => {
        if (typeof $.fn.datepicker !== 'undefined') {
            // Configuración en español para el datepicker
            $.fn.datepicker.dates['es'] = {
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
                months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                today: "Hoy",
                clear: "Borrar",
                format: "dd/mm/yyyy",
                titleFormat: "MM yyyy",
                weekStart: 1
            };

            // Aplicar datepicker a los campos de fecha
            $('input[name="start_date"], input[name="end_date"]').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'es'
            });
        }
    };

    // Limpiar campos de fecha
    const setupDateClear = () => {
        const clearButton = document.getElementById('clear-dates');
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                $('input[name="start_date"], input[name="end_date"]').val('');
            });
        }
    };

    // Validación del campo de salto de página
    const setupPageInputValidation = () => {
        const pageInputs = document.querySelectorAll('.entrances-page-input');
        
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

    // Validación de formulario de fechas
    const setupDateFormValidation = () => {
        const dateForm = document.querySelector('.entrances-date-filter-form');
        
        if (dateForm) {
            dateForm.addEventListener('submit', function(e) {
                const startDate = this.querySelector('input[name="start_date"]').value;
                const endDate = this.querySelector('input[name="end_date"]').value;
                
                if ((startDate && !endDate) || (!startDate && endDate)) {
                    e.preventDefault();
                    alert('Por favor, complete ambas fechas o deje ambas vacías.');
                    return false;
                }
            });
        }
    };

    // Inicialización de todas las funcionalidades
    const initEntrances = () => {
        initializeDatepicker();
        setupDateClear();
        setupPageInputValidation();
        setupDateFormValidation();
    };

    // Inicializar cuando el DOM esté listo
    initEntrances();
});