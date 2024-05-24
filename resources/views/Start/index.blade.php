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
        <p class="lead">Bienvenido a la página de inicio de la aplicación de inventario.</p>
        
        @if(isset($counts['count']))
            <p class="lead">El número de préstamos es: {{ $counts['count'] }}</p>
        @else
            <p class="lead">No se pudo obtener el número de préstamos.</p>
        @endif

        @if(isset($products['count']))
            <p class="lead">El número de productos es: {{ $products['count'] }}</p>
        @else
            <p class="lead">No se pudo obtener el número de productos.</p>
        @endif

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

        @if(session()->has('role'))
            @php
                $role = session('role');
                $roleName = '';
                if ($role == 0) {
                    $roleName = 'Administrador';
                } elseif ($role == 1) {
                    $roleName = 'Trabajador 1';
                } elseif ($role == 2) {
                    $roleName = 'Trabajador 2';
                }
            @endphp
            <p class="lead">Rol: {{ $roleName }}</p>
        @else
            <p class="lead">No se pudo obtener el rol del usuario.</p>
        @endif

        @if(session()->has('name'))
            <p class="lead">Nombre: {{ session('name') }}</p>
        @else
            <p class="lead">No se pudo obtener el nombre del usuario.</p>
        @endif

        @if(session()->has('email'))
            <p class="lead">Email: {{ session('email') }}</p>
        @else
            <p class="lead">No se pudo obtener el email del usuario.</p>
        @endif

    </div>
</div>
</body>
</html>
@endsection
