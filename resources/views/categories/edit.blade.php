@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Editar Categoría</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('categories.update', $category['id']) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ $category['name'] }}" required class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" required class="form-control">{{ $category['description'] }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@endsection
