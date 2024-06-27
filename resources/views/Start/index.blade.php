@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Inicio</h1>
        <p class="lead">
            Bienvenido 
            @if(session()->has('name'))
                <strong>{{ session('name') }}</strong>
            @else
                Usuario no encontrado
            @endif
            a la página de inicio de la aplicación de inventario.
        </p>
        {{-- <p class="lead"><strong>ID:</strong> {{ session('user_id', 'No se pudo obtener el ID del usuario.') }}</p> --}}

        @php
            $roleNames = ['Administrador', 'Trabajador Admin', 'Trabajador'];
            $role = session('role', -1);
        @endphp
        <p class="lead"><strong>Rol: </strong> {{ $role >= 0 && $role < count($roleNames) ? $roleNames[$role] : 'Rol no identificado' }}</p>

        <p class="lead"><strong>Email:</strong> {{ session('email', 'No se pudo obtener el email del usuario.') }}</p>
        <div id="data-container">
            <!-- Aquí se cargarán los datos -->
            <p class="lead">Cargando datos...</p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{ route('start.getData') }}',
            method: 'GET',
            success: function(data) {
                let content = '';
                content += `<p class="lead"><strong>El número de productos es:</strong> ${data.products.count}</p>`;
                content += `<p class="lead"><strong>El número de entradas es:</strong> ${data.entrance.count}</p>`;
                content += `<p class="lead"><strong>El número de salidas es:</strong> ${data.out.count}</p>`;
                content += `<p class="lead"><strong>El número de préstamos activos es:</strong> ${data.counts.count}</p>`;
                content += `<p class="lead"><strong>El número de préstamos finalizados es:</strong> ${data.countsFinished.count}</p>`;
                content += `<p class="lead"><strong>El número total de préstamos es:</strong> ${data.countsAll.count}</p>`;
                content += `<p class="lead"><strong>El producto con más entradas es:</strong> ${data.countsProductEntrance.name || 'Ninguno'} <strong>cantidad:</strong> ${data.countsProductEntrance.total_quantity || '0'}</p>`;
                content += `<p class="lead"><strong>El producto con más salidas es:</strong> ${data.countsProductOut.name || 'Ninguno'} <strong>cantidad:</strong> ${data.countsProductOut.total_quantity || '0'}</p>`;
                content += `<p class="lead"><strong>El producto con más préstamos es:</strong> ${data.countsProductLoan.name || 'Ninguno'} <strong>cantidad:</strong> ${data.countsProductLoan.total_quantity || '0'}</p>`;
                $('#data-container').html(content);
            },
            error: function() {
                $('#data-container').html('<p>No se pudo cargar los datos.</p>');
            }
        });
    });
</script>
@endsection
