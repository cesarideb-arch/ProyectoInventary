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
        <p class="lead">Bienvenido 
            @if(session()->has('name'))
                <strong>{{ session('name') }}</strong>
            @else
                Usuario no encontrado
            @endif
            a la página de inicio de la aplicación de inventario.</p>

        @php
            $role = session('role');
            $roleName = ($role == 0) ? 'Administrador' : (($role == 1) ? 'Trabajador rango 1' : (($role == 2) ? 'Trabajador rango 2' : ''));
        @endphp
        <p class="lead"><strong>Rol: </strong> {{ $roleName }}</p>

        <p class="lead"><strong>Email:</strong> {{ session('email', 'No se pudo obtener el email del usuario.') }}</p>

        <p class="lead"><strong>El número de préstamos es:</strong> {{ $counts['count'] ?? 'No se pudo obtener el número de préstamos.' }}</p>

        <p class="lead"><strong>El número de productos es: </strong>{{ $products['count'] ?? 'No se pudo obtener el número de productos.' }}</p>

        <p class="lead"><strong>El número de entradas es: </strong>{{ $countEntrances['count'] ?? 'No se pudo obtener el número de entradas.' }}</p>

        <p class="lead"><strong>El número de salidas es: </strong>{{ $countOutputs['count'] ?? 'No se pudo obtener el número de salidas.' }}</p>

        @if(isset($entranceProduct))
            <p class="lead">Producto con mayor cantidad de entradas:</p>
            <ul>
                <li>Nombre: {{ $entranceProduct['name'] }}</li>
                <li>Cantidad: {{ $entranceProduct['quantity'] }}</li>
                {{-- <li>Marca: {{ $entranceProduct['brand'] }}</li> --}}
                <!-- Agrega aquí más detalles según sea necesario -->
            </ul>
        @else
            <p class="lead">No se pudo obtener el producto con mayor cantidad de entradas.</p>
        @endif

        @if(isset($outputProduct))
            <p class="lead">Producto con mayor cantidad de salidas:</p>
            <ul>
                <li>Nombre: {{ $outputProduct['name'] }}</li>
                <li>Cantidad: {{ $outputProduct['quantity'] }}</li>
                {{-- <li>Marca: {{ $outputProduct['brand'] }}</li> --}}
                <!-- Agrega aquí más detalles según sea necesario -->
            </ul>
        @else
            <p class="lead">No se pudo obtener el producto con mayor cantidad de salidas.</p>
        @endif

        @if(isset($loanProduct))
            <p class="lead">Producto con mayor cantidad de préstamos:</p>
            <ul>
                <li>Nombre: {{ $loanProduct['name'] }}</li>
                <li>Cantidad: {{ $loanProduct['quantity'] }}</li>
                {{-- <li>Marca: {{ $loanProduct['brand'] }}</li> --}}
                <!-- Agrega aquí más detalles según sea necesario -->
            </ul>
        @else
            <p class="lead">No se pudo obtener el producto con mayor cantidad de préstamos.</p>
        @endif
    </div>
</div>
</body>
</html>
@endsection
