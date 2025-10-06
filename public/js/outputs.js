// resources/js/outputs.js
document.addEventListener('DOMContentLoaded', function() {
    // Inicialización del datepicker
    const initializeDatepicker = () => {
        if (typeof $.fn.datepicker !== 'undefined') {
            // Configuración en español para el datepicker
            $.fn.datepicker.dates['es'] = {
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                today: "Hoy",
                clear: "Borrar",
                format: "dd/mm/yyyy",
                titleFormat: "MM yyyy",
                weekStart: 1
            };

            // Aplicar datepicker a los campos de fecha
            $('.outputs-datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'es'
            });
        }
    };

    // Limpiar campos de fecha
    const setupDateClear = () => {
        const clearButton = document.getElementById('outputs-clear-dates');
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                $('input[name="start_date"], input[name="end_date"]').val('');
            });
        }
    };

    // Validación del campo de salto de página
    const setupPageInputValidation = () => {
        const pageInputs = document.querySelectorAll('.outputs-page-input');
        
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

    // Función para manejar la descarga de PDF con filtros
    const setupPdfDownload = () => {
        const pdfButtons = document.querySelectorAll('.outputs-pdf-download, .outputs-excel-download');
        
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
    const initOutputs = () => {
        initializeDatepicker();
        setupDateClear();
        setupPageInputValidation();
        setupPdfDownload();
    };

    // Inicializar cuando el DOM esté listo
    initOutputs();
});