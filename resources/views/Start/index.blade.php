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

        @php
            $roleNames = ['Administrador', 'Trabajador Administrador', 'Trabajado'];
            $role = session('role', -1);
        @endphp
        <p class="lead"><strong>Rol: </strong> {{ $role >= 0 && $role < count($roleNames) ? $roleNames[$role] : 'Rol no identificado' }}</p>

        <p class="lead"><strong>Email:</strong> {{ session('email', 'No se pudo obtener el email del usuario.') }}</p>

        @php
            $countData = [
                'El número de préstamos es:' => $counts['count'] ?? 'No se pudo obtener el número de préstamos.',
                'El número de productos es:' => $products['count'] ?? 'No se pudo obtener el número de productos.',
                'El número de entradas es:' => $countEntrances['count'] ?? 'No se pudo obtener el número de entradas.',
                'El número de salidas es:' => $countOutputs['count'] ?? 'No se pudo obtener el número de salidas.',
            ];
        @endphp
        @foreach($countData as $label => $count)
            <p class="lead"><strong>{{ $label }}</strong> {{ $count }}</p>
        @endforeach

        @php
            $productData = [
            'Producto con mayor cantidad de entradas:' => $entranceProduct ?? null,
            'Producto con mayor cantidad de salidas:' => $outputProduct ?? null,
            'Producto con mayor cantidad de préstamos:' => $loanProduct ?? null,
            ];
        @endphp
        @foreach($productData as $label => $product)
            @if($product)
                <p class="lead">{{ $label }}</p>
                <ul>
                    <li>Nombre: {{ $product['name'] ?? 'Nombre no disponible' }}</li>
                    <li>Cantidad: {{ $product['total_quantity'] ?? 'Cantidad no disponible' }}</li>
                </ul>
            @else
                <p class="lead">No se pudo obtener el {{ strtolower($label) }}</p>
            @endif
        @endforeach
    </div>
</div>
</body>
</html>
@endsection
