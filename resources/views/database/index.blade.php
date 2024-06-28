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
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            margin-top: 1rem;
        }
        .custom-file-label::after {
            content: "Browse";
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="jumbotron text-center">
        <h1 class="display-4">Generar Backup de Base de Datos</h1>
        <p class="lead">Haga clic en el botón a continuación para generar una copia de seguridad de la base de datos.</p>
        <button id="backupButton" class="btn btn-primary btn-lg mt-3">Generar Backup</button>
        <div id="responseMessage"></div>
    </div>

    <div class="jumbotron text-center mt-5">
        <h1 class="display-4">Importar Base de Datos</h1>
        <p class="lead">Seleccione un archivo SQL para importar a la base de datos.</p>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="importForm" enctype="multipart/form-data" class="mt-4">
            <div class="custom-file mb-3">
                <input type="file" class="custom-file-input" id="database_file" name="database_file" accept=".sql" required>
                <label class="custom-file-label" for="database_file">Choose file</label>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Importar</button>
        </form>
        <div id="importResponseMessage"></div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
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

        $('#importForm').on('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let token = '{{ $token }}';
            let apiImport = '{{ $apiImport }}';

            fetch(apiImport, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Authorization': `Bearer ${token}`
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#importResponseMessage').html('<div class="alert alert-success">Database imported successfully!</div>');
                } else {
                    $('#importResponseMessage').html('<div class="alert alert-danger">Failed to import database. Error: ' + data.message + '</div>');
                }
                setTimeout(function() {
                    $('#importResponseMessage').fadeOut('slow', function() {
                        $(this).html('').show();
                    });
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                $('#importResponseMessage').html('<div class="alert alert-danger">An error occurred while importing the database.</div>');
                setTimeout(function() {
                    $('#importResponseMessage').fadeOut('slow', function() {
                        $(this).html('').show();
                    });
                }, 3000);
            });
        });

        // Mostrar el nombre del archivo seleccionado
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    });
</script>
@endsection
