@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div>
        <h1 class="mb-4">Inicio</h1>
        <p>Bienvenido a la página de inicio de la aplicación de inventario.</p>
        @if(isset($counts['count']))
            <p>El número de préstamos es: {{ $counts['count'] }}</p>
        @else
            <p>No se pudo obtener el número de préstamos.</p>
        @endif

        @if(isset($products['count']))
            <p>El número de productos es: {{ $products['count'] }}</p>
        @else
            <p>No se pudo obtener el número de productos.</p>
        @endif
    </div>

</body>
</html>
@endsection
