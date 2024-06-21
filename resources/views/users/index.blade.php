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
            justify-content: center; /* Centra horizontalmente */
            align-items: center;
        }

        .btn-group-horizontal .btn {
            margin-right: 5px;
        }

        .btn-custom-size {
            padding: 6px 12px;
        }

        .btn-danger {
            background-color: #ff0000; /* color rojo */
            border-color: #ff0000; /* color rojo */
            color: #fff; /* texto blanco */
        }

        .btn-danger:hover {
            background-color: #cc0000; /* color rojo más oscuro al pasar el mouse */
            border-color: #cc0000; /* color rojo más oscuro al pasar el mouse */
        }

        @media (max-width: 576px) {
            .ml-auto {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .table thead {
                display: none;
            }
            .table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 15px;
            }
            .table td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                border: 1px solid #ddd;
            }
            .table td::before {
                content: attr(data-label);
                font-weight: bold;
            }
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
                    <th>Rol</th>
                    <th style="text-align: center;" colspan="2">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                    <td data-label="Nombre">{{ $user['name'] }}</td>
                    <td data-label="Email">{{ $user['email'] }}</td>
                    <td data-label="Rol">
                        @if($user['role'] == 1)
                            Admin Trabajador
                        @elseif($user['role'] == 2)
                            Trabajador
                        @endif
                    </td>
                    <td data-label="Acciones" class="text-center">
                        <div class="btn-group btn-group-horizontal" role="group">
                            <form action="{{ route('users.edit', $user['id']) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-custom-size">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger btn-custom-size delete-button" data-id="{{ $user['id'] }}" style="background-color: red;">
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
                            <div class="input-group">
                                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('admin_password', this)">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" style="background-color: red;">Eliminar</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
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

    function togglePasswordVisibility(fieldId, toggleButton) {
        var passwordField = document.getElementById(fieldId);
        var passwordFieldType = passwordField.getAttribute('type');
        var toggleIcon = toggleButton.querySelector('i');
        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordField.setAttribute('type', 'password');
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }
    </script>
</body>
</html>
@endsection
