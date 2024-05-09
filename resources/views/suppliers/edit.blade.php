@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Editar Proveedor</h1>
        <form action="{{ route('suppliers.update', $supplier['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="article">Artículo</label>
                <input type="text" class="form-control" id="article" name="article" value="{{ $supplier['article'] }}" required>
            </div>
            <div class="form-group">
                <label for="price">Precio</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ $supplier['price'] }}" required>
            </div>
            <div class="form-group">
                <label for="company">Empresa</label>
                <input type="text" class="form-control" id="company" name="company" value="{{ $supplier['company'] }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $supplier['phone'] }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $supplier['email'] }}" required>
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $supplier['address'] }}" required>
            </div>
            <!-- Agregar más campos si es necesario -->

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@endsection
