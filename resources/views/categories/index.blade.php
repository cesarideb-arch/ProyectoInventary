@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorías</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Lista de Categorías</h1>
        <div class="mb-3">
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
        </div>
        <form method="GET" action="{{ route('categories.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Categorías..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button class="btn" type="submit" style="background-color: #333 ; color: rgb(255, 255, 255);">Buscar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            @if(isset($categories) && count($categories) > 0)
            <table class="table table-striped">
            <thead>
                <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th style="text-align: center;" colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                <td>{{ $category['name'] }}</td>
                <td>{{ $category['description'] }}</td>
                <td style="text-align: center;">
                    <div class="btn-group btn-group-horizontal text-center" role="group">
                    <form action="{{ route('categories.edit', $category['id']) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-custom-size">Editar</button>
                    </form>
                    <form action="{{ route('categories.destroy', $category['id']) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-custom-size"><i class="fas fa-trash"></i></button>
                    </form>
                    </div>
                </td>
                </tr>
                @endforeach
            </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('categories.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                    <a href="{{ route('categories.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-secondary {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($currentPage < $lastPage)
                    <a href="{{ route('categories.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                <a href="{{ route('categories.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i>
                </a>            </div>
            </div>
            @else
            <p>No se encontraron categorías.</p>
            @endif
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}'
        });
    </script>
    @endif

    <script>
    const deleteForms = document.querySelectorAll('.delete-form'); // Selecciona todos los formularios de eliminar

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene la acción predeterminada del formulario
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar esta categoría?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviar el formulario
                    form.submit();
                }
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
