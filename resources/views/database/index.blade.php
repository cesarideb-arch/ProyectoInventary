@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Base de Datos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .jumbotron {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .alert {
            margin-top: 1rem;
        }
        .custom-file-label::after {
            content: "Browse";
        }
        .section-title {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        .backup-info {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="jumbotron text-center">
        <h1 class="display-4">Gestión de Base de Datos</h1>
        <p class="lead">Realice operaciones de backup, importación y restablecimiento de la base de datos.</p>
    </div>

    <!-- Sección de Backup -->
    <div class="jumbotron">
        <h2 class="section-title">Exportar Base de Datos</h2>
        <p>Haga clic en el botón a continuación para generar una copia de seguridad de la base de datos.</p>
        <button id="backupButton" class="btn btn-primary btn-lg mt-3">Generar Backup</button>
        <div id="backupMessage"></div>
    </div>

    <!-- Sección de Importación -->
    <div class="jumbotron">
        <h2 class="section-title">Importar Base de Datos</h2>
        <p>Seleccione un archivo SQL para importar a la base de datos.</p>
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" id="sqlFile" accept=".sql,.txt">
            <label class="custom-file-label" for="sqlFile">Seleccionar archivo SQL</label>
        </div>
        <button id="importButton" class="btn btn-primary btn-lg mt-3" disabled>Importar Base de Datos</button>
        <div id="importMessage"></div>
    </div>

    
    <div class="jumbotron">
        <h2 class="section-title">Restablecer Base de Datos</h2>
        <p class="text-danger">¡Advertencia! Esta acción eliminará todos los datos de la base de datos y no se puede deshacer.</p>
        <button id="resetButton" class="btn btn-danger btn-lg mt-3" data-toggle="modal" data-target="#confirmResetModal">Restablecer Base de Datos</button>
        <div id="resetMessage"></div>
    </div>
</div>

<!-- Modal de confirmación para restablecimiento -->
<div class="modal fade" id="confirmResetModal" tabindex="-1" role="dialog" aria-labelledby="confirmResetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmResetModalLabel">Confirmar Restablecimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea restablecer la base de datos? Esta acción eliminará todos los datos y no se puede deshacer.</p>
                <p class="text-danger"><strong>¡Esta operación es irreversible!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmReset">Sí, Restablecer</button>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Mostrar nombre de archivo seleccionado
        $('#sqlFile').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
            $('#importButton').prop('disabled', !fileName);
        });

        // Backup de base de datos
         $(document).ready(function() {
        $('#backupButton').on('click', function() {
            let apiUrl = "{{ $apibakcup }}";
            let token = "{{ $token }}";

            $.ajax({
                url: apiUrl,
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                success: function(data) {
                    $('#responseMessage').html('<div class="alert alert-success">Backup generado exitosamente en descargas.</div>');
                    setTimeout(function() {
                        $('#responseMessage').fadeOut('slow', function() {
                            $(this).html('').show();
                        });
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    $('#responseMessage').html('<div class="alert alert-danger">Error al generar el backup.</div>');
                    setTimeout(function() {
                        $('#responseMessage').fadeOut('slow', function() {
                            $(this).html('').show();
                        });
                    }, 3000);
                }
            });
        });

    });

        // Importar base de datos
        $('#importButton').on('click', function() {
            let apiUrl = "{{ $apiimport }}";
            let token = "{{ $token }}";
            let fileInput = document.getElementById('sqlFile');
            
            if (fileInput.files.length === 0) {
                showAlert('importMessage', 'Por favor, seleccione un archivo SQL.', 'danger');
                return;
            }

            let $button = $(this);
            let originalText = $button.html();
            $button.html('<span class="spinner-border spinner-border-sm"></span> Importando...');
            $button.prop('disabled', true);

            showAlert('importMessage', 'Importando base de datos, por favor espere...', 'info');

            let formData = new FormData();
            formData.append('sql_file', fileInput.files[0]);

            $.ajax({
                url: apiUrl,
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showAlert('importMessage', response.message, 'success');
                    
                    // Limpiar formulario
                    $('#sqlFile').val('');
                    $('.custom-file-label').html('Seleccionar archivo SQL');
                    $button.html(originalText);
                    $button.prop('disabled', true);

                    // Cerrar sesión después de 3 segundos si es requerido
                    if (response.logout_required) {
                        setTimeout(() => {
                            showAlert('importMessage', 'Cerrando sesión automáticamente...', 'warning');
                            setTimeout(() => {
                                window.location.href = "{{ url('/login') }}";
                            }, 2000);
                        }, 3000);
                    }
                },
                error: function(xhr) {
                    $button.html(originalText);
                    $button.prop('disabled', false);
                    let errorMsg = xhr.responseJSON?.error || 'Error desconocido';
                    showAlert('importMessage', 'Error al importar la base de datos: ' + errorMsg, 'danger');
                }
            });
        });

        // Restablecer base de datos
        $('#confirmReset').on('click', function() {
            let apiUrl = "{{ $apireset }}";
            let token = "{{ $token }}";

            let $button = $(this);
            let originalText = $button.html();
            $button.html('<span class="spinner-border spinner-border-sm"></span> Restableciendo...');
            $button.prop('disabled', true);

            $('#confirmResetModal').modal('hide');
            showAlert('resetMessage', 'Restableciendo base de datos, por favor espere...', 'info');

            $.ajax({
                url: apiUrl,
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                success: function(response) {
                    showAlert('resetMessage', response.message, 'success');
                    
                    // Cerrar sesión después de 3 segundos si es requerido
                    if (response.logout_required) {
                        setTimeout(() => {
                            showAlert('resetMessage', 'Cerrando sesión automáticamente...', 'warning');
                            setTimeout(() => {
                                window.location.href = "{{ url('/login') }}";
                            }, 2000);
                        }, 3000);
                    }
                },
                error: function(xhr) {
                    $button.html(originalText);
                    $button.prop('disabled', false);
                    let errorMsg = xhr.responseJSON?.error || 'Error desconocido';
                    showAlert('resetMessage', 'Error al restablecer la base de datos: ' + errorMsg, 'danger');
                }
            });
        });

        // Función auxiliar para mostrar alertas
        function showAlert(containerId, message, type) {
            let icon = '';
            switch(type) {
                case 'success': icon = '✓'; break;
                case 'danger': icon = '❌'; break;
                case 'warning': icon = '⚠️'; break;
                default: icon = 'ℹ️';
            }
            
            let html = `<div class="alert alert-${type} alert-dismissible fade show">
                ${icon} ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>`;
            $('#' + containerId).html(html);
        }

        // Función para formatear tamaño de archivo
        function formatFileSize(bytes) {
            if (!bytes || bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });

    // Función para copiar enlace al portapapeles
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Mostrar mensaje de éxito
            let alertDiv = $('<div class="alert alert-info alert-dismissible fade show mt-2">📋 Enlace copiado al portapapeles</div>');
            $('#backupMessage').append(alertDiv);
            setTimeout(() => alertDiv.alert('close'), 3000);
        }, function(err) {
            console.error('Error al copiar: ', err);
        });
    }
</script>
</body>
</html>
@endsection