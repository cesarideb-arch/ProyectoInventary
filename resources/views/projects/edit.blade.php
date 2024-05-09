@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Editar Proyecto</h1>
        <form method="POST" action="{{ route('projects.update', $project['id']) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $project['name'] }}">
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea class="form-control" id="description" name="description">{{ $project['description'] }}</textarea>
            </div>
            <div class="form-group">
                <label for="company_name">Nombre de la Empresa:</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $project['company_name'] }}">
            </div>
            <div class="form-group">
                <label for="rfc">RFC:</label>
                <input type="text" class="form-control" id="rfc" name="rfc" value="{{ $project['rfc'] }}">
            </div>
            <div class="form-group">
                <label for="address">Dirección:</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $project['address'] }}">
            </div>
            <div class="form-group">
                <label for="phone_number">Teléfono:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $project['phone_number'] }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $project['email'] }}">
            </div>
            <div class="form-group">
                <label for="client_name">Nombre del Cliente:</label>
                <input type="text" class="form-control" id="client_name" name="client_name" value="{{ $project['client_name'] }}">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@endsection
