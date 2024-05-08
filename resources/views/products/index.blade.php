@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Inventario General</h1>
        <div class="mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Agregar Producto</a>
        </div>
        @if(isset($products) && count($products) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th style="text-align: center;" colspan="3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['description'] }}</td>
                            <td>${{ $product['price'] }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('products.edit', $product['id']) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Editar</button>
                                    </form>
                                </div>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('products.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="submit" class="btn btn-success">Salida</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No se encontraron productos.</p>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
