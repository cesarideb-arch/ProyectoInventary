@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
            background-color: #f5f7f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        h1 {
            color: #000000ff;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #000000ff;
            color: white;
        }
        
        .btn-danger {
            background: #e53e3e;
            color: white;
        }
        
        .btn-success {
            background: #38a169;
            color: white;
        }
        
        .btn-info {
            background: #000000ff;
            color: white;
        }
        
        .btn-outline-primary {
            border: 1px solid #000000ff;
            color: #000000ff;
            background: transparent;
        }
        
        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }
        
        .btn-custom-size {
            padding: 8px 16px;
        }

        .search-container {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .input-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-radius: 6px;
        }
        
        .form-control {
            border: 1px solid #cbd5e0;
            padding: 10px 15px;
            height: auto;
            font-size: 14px;
        }
        
        .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background: #000000ff;
            color: white;
        }

        /* ESTILOS PARA LA TABLA (COMO LA IMAGEN) CON CABECERA FIJA */
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
            max-height: 500px; /* Limitamos la altura para activar scroll */
            overflow-y: auto; /* Habilitamos scroll vertical */
            position: relative; /* Necesario para el sticky header */
        }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 14px;
        }
        
        .custom-table thead {
            background-color: #000000ff; /* Fondo oscuro como en la imagen */
            position: sticky; /* Hacemos el header sticky */
            top: 0; /* Lo fijamos en la parte superior del contenedor */
            z-index: 10; /* Aseguramos que esté por encima del contenido */
        }
        
        .custom-table th {
            padding: 12px 15px;
            text-align: center;
            color: white;
            font-weight: 600;
            border-bottom: 2px solid #000000ff;
            position: sticky; /* También aplicamos sticky a cada th */
            top: 0;
            background-color: #000000ff; /* Mantenemos el color de fondo */
            z-index: 11; /* Aseguramos que esté por encima del thead */
        }
        
        .custom-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            text-align: center;
        }
        
        .custom-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .custom-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .custom-table tbody tr:hover {
            background-color: #ebf8ff;
        }
        
        .table-action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            transition: all 0.2s;
        }
        
        .table-btn-edit {
            background: #000000ff;
            color: white;
        }
        
        .table-btn-delete {
            background: #e53e3e;
            color: white;
        }
        
        .table-btn-edit:hover, .table-btn-delete:hover {
            opacity: 0.85;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .pagination-info {
            font-size: 14px;
            color: #000000ff;
        }

        .pagination {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .pagination span {
            margin: 0 5px;
        }

        .pagination a {
            margin: 0 3px;
        }
        
        .pagination .active {
            font-weight: bold;
            background: #000000ff;
            color: white;
        }

        .pagination .page-input {
            width: 50px;
            display: inline-block;
            margin: 0 5px;
            text-align: center;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            padding: 5px;
            font-size: 14px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-admin {
            background-color: #ffeaa7;
            color: #d35400;
        }

        .status-admin-trabajador {
            background-color: #a3a3a3;
            color: white;
        }

        .status-trabajador {
            background-color: #d6eaf8;
            color: #2c5282;
        }

        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: #000000ff;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .modal-title {
            font-weight: 600;
        }

        .close {
            color: white;
            opacity: 0.8;
        }

        .close:hover {
            color: white;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            /* Ajustes para móviles en la tabla */
            .table-container {
                max-height: 400px; /* Reducimos la altura máxima en móviles */
            }
        }

        @media (max-width: 576px) {
            .table-container {
                overflow-x: auto;
                max-height: none; /* En móviles quitamos la altura fija para la vista de tarjetas */
            }
            
            .custom-table thead {
                display: none; /* Ocultamos el header en móviles */
            }
            
            .custom-table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 15px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 10px;
            }
            
            .custom-table td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                border: none;
                border-bottom: 1px solid #eee;
                text-align: right;
            }
            
            .custom-table td:last-child {
                border-bottom: none;
            }
            
            .custom-table td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 10px;
                color: #2d3748;
                min-width: 100px;
                text-align: left;
            }
            
            .action-buttons {
                justify-content: center;
                width: 100%;
            }
            
            .btn-group-horizontal {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Usuarios</h1>
        
        <div class="d-flex justify-content-between align-items-center page-header mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
        </div>

        <div class="search-container">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Buscar Usuarios..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="table-container">
            @if(isset($users) && count($users) > 0)
            <table class="custom-table">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                    <td data-label="Nombre">{{ $user['name'] }}</td>
                    <td data-label="Email">{{ $user['email'] }}</td>
                    <td data-label="Rol">
                        @if($user['role'] == 0)
                            <span class="status-badge status-admin">Administrador Dueño</span>
                        @elseif($user['role'] == 1)
                            <span class="status-badge status-admin-trabajador">Administrador Trabajador</span>
                        @elseif($user['role'] == 2)
                            <span class="status-badge status-trabajador">Trabajador</span>
                        @endif
                    </td>
                    <td data-label="Acciones">
                        <div class="action-buttons">
                            <form action="{{ route('users.edit', $user['id']) }}" method="GET">
                                @csrf
                                <button type="submit" class="table-action-btn table-btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            @if($user['role'] != 0)
                            <button class="table-action-btn table-btn-delete delete-button" data-id="{{ $user['id'] }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            <div class="pagination-container">
                <div class="pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('users.index', ['page' => 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('users.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Anterior</a>
                    @endif
                    
                    <!-- Mostrar páginas cercanas -->
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="btn btn-primary active btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('users.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-outline-primary btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <!-- Saltar a página específica -->
                    <form method="GET" action="{{ route('users.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control page-input" placeholder="Ir a">
                        <button type="submit" class="btn btn-info btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('users.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Siguiente</a>
                        <a href="{{ route('users.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @else
            <p class="text-center py-4">No se encontraron usuarios.</p>
            @endif
        </div>
    </div>

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
                            <label for="admin_password">Contraseña de Administrador Dueño</label>
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
            text: '{{ session('success') }}',
            background: '#f7fafc',
            confirmButtonColor: '#000000ff'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            background: '#f7fafc',
            confirmButtonColor: '#000000ff'
        });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                        background: '#f7fafc',
                        confirmButtonColor: '#000000ff'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: data.message,
                        background: '#f7fafc',
                        confirmButtonColor: '#000000ff'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Error al eliminar el usuario. Por favor, inténtalo de nuevo más tarde.',
                    background: '#f7fafc',
                    confirmButtonColor: '#000000ff'
                });
            });
        });
        
        // Validación del campo de salto de página
        document.querySelector('.page-input')?.addEventListener('change', function() {
            const maxPage = {{ $lastPage }};
            if (this.value > maxPage) {
                this.value = maxPage;
            } else if (this.value < 1) {
                this.value = 1;
            }
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