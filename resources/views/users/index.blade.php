@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .btn-group-horizontal {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-group-horizontal .btn {
            margin-right: 5px;
            width: 36px; /* Tamaño fijo para los botones */
            height: 36px; /* Tamaño fijo para los botones */
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .btn-group-horizontal .btn i {
            font-size: 16px; /* Tamaño del ícono */
        }
        .table-responsive {
            margin-top: 10px;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }
        .pagination {
            display: flex;
            justify-content: flex-start; /* Cambiado para alinear a la izquierda */
            margin-top: 20px;
        }
        .pagination .btn {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Usuarios</h1>
        <div class="mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
        </div>
        <form method="GET" action="{{ route('users.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Usuarios..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($users) && count($users) > 0)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th style="text-align: center;" colspan="2">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        <div class="btn-group btn-group-horizontal text-center" role="group">
                            <form action="{{ route('users.edit', $user['id']) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-custom-size">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger btn-custom-size delete-button" data-id="{{ $user['id'] }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('users.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                    <a href="{{ route('users.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-secondary {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($currentPage < $lastPage)
                    <a href="{{ route('users.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                <a href="{{ route('users.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            </div>
            @else
            <p>No se encontraron usuarios.</p>
            @endif
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este usuario?</p>
                        <div class="form-group">
                            <label for="admin_password">Contraseña de Administrador</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
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

    <!-- JavaScript para la ventana emergente de confirmación de eliminación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const form = document.getElementById('deleteForm');
                form.action = `/users/${userId}`;
                $('#deleteModal').modal('show');
            });
        });

        document.getElementById('deleteForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = this;
            const data = new FormData(form);

            // Realizar solicitud AJAX para eliminar el usuario
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': data.get('_token'),
                    'Accept': 'application/json',
                },
                body: data,
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Usuario eliminado con éxito') {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Error al eliminar el usuario. Por favor, inténtalo de nuevo más tarde.',
                });
            });
        });
    });
    </script>
</body>
</html>
@endsection
