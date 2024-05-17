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
    </div>
</div>
        </div>

</body>
</html>
@endsection
