@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Generar backup de base de datos</h1>
        <button id="backupButton" class="btn btn-primary">Generar Backup</button>
        <div id="responseMessage" class="mt-3"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#backupButton').on('click', function() {
            let apiUrl = "{{ $apibakcup }}";
            let token = "{{ $token }}";

            console.log('API URL:', apiUrl);
            console.log('Token:', token);

            $.ajax({
                url: apiUrl,
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                success: function(data) {
                    console.log('Response data:', data);
                    $('#responseMessage').html('<div class="alert alert-success">Backup generado exitosamente en descargas.</div>');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#responseMessage').html('<div class="alert alert-danger">Error al generar el backup.</div>');
                }
            });
        });
    });
</script>
</body>
</html>
@endsection
