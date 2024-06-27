@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Generar Backup</title>
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
</div>

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
    });
</script>
</body>
</html>
@endsection
