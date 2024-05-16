@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proyectos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Lista de Proyectos</h1>
        <div class="mb-3">
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
        </div>
        <form method="GET" action="{{ route('projects.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar proyectos..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($projects) && count($projects) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Nombre de la Empresa</th>
                        <th>RFC</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Nombre del Cliente</th>
                        <th style="text-align: center;" colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td>{{ $project['name'] }}</td>
                        <td>{{ $project['description'] }}</td>
                        <td>{{ $project['company_name'] }}</td>
                        <td>{{ $project['rfc'] }}</td>
                        <td>{{ $project['address'] }}</td>
                        <td>{{ $project['phone_number'] }}</td>
                        <td>{{ $project['email'] }}</td>
                        <td>{{ $project['client_name'] }}</td>
                        <td>
                            <div class="btn-group btn-group-horizontal text-center" role="group">
                                <form action="{{ route('projects.edit', $project['id']) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-custom-size">Editar</button>
                                </form>
                                <form action="{{ route('projects.destroy', $project['id']) }}" method="POST" class="delete-form">
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
            @else
            <p>No se encontraron proyectos.</p>
            @endif
        </div>
    </div>

    <!-- JavaScript para la ventana emergente de confirmación de eliminación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Quieres eliminar este proyecto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@endsection
