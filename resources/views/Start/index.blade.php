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
            $roleNames = ['Administrador', 'Trabajador Admin', 'Trabajador'];
            $role = session('role', -1);
        @endphp
        <p class="lead"><strong>Rol: </strong> {{ $role >= 0 && $role < count($roleNames) ? $roleNames[$role] : 'Rol no identificado' }}</p>

        <p class="lead"><strong>Email:</strong> {{ session('email', 'No se pudo obtener el email del usuario.') }}</p>

           @php
                $countData = [
                    'El número de préstamos es:' => $counts['count'] ?? ($counts['count'] === 0 ? 'No se pudo obtener el número de préstamos.' : ''),
                    'El número de productos es:' => $products['count'] ?? ($products['count'] === 0 ? 'No se pudo obtener el número de productos.' : ''),
                    'El número de entradas es:' => $entrance['count'] ?? 'No se pudo obtener el número de entradas de productos.',
                    'El número de salidas es:' => $out['count'] ?? 'No se pudo obtener el número de salidas de productos.',
                    'El producto con más entradas es:' => ($countsProductEntrance['name'] ?? 'No se pudo obtener el nombre del producto con más entradas.')  . 
                    ' <strong>' . 'cantidad' . '</strong> ' . ($countsProductEntrance['total_quantity'] ?? ''),
                    'El producto con más salidas es:' => ($countsProductOut['name'] ?? 'No se pudo obtener el nombre del producto con más salidas.') .
                    ' <strong>' . 'cantidad' . '</strong> ' . ($countsProductOut['total_quantity'] ?? ''),
                    'El producto con más Prestamos es:' => ($countsProductLoan['name'] ?? 'No se pudo obtener el nombre del producto con más prestamos.') .
                    ' <strong>' . 'cantidad' . '</strong> ' . ($countsProductLoan['total_quantity'] ?? ''),
                ];
                @endphp

        @foreach($countData as $label => $count)
            <p class="lead"><strong>{{ $label }}</strong> {!! $count !!}</p>
        @endforeach

    </div>
</div>
</body>
</html>
@endsection
