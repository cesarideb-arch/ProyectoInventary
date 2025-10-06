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
    <link href="{{ asset('css/users.css') }}" rel="stylesheet">
</head>
<body class="users-container">
    <div class="users-content">
        <h1 class="users-title">Usuarios</h1>
        
        <div class="d-flex justify-content-between align-items-center users-page-header mb-3">
            <a href="{{ route('users.create') }}" class="users-btn users-btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
        </div>

        <div class="users-search-container">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="users-input-group">
                    <input type="text" name="query" class="users-form-control form-control" placeholder="Buscar Usuarios..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="users-btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="users-table-container">
            @if(isset($users) && count($users) > 0)
            <table class="users-table">
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
                            <span class="users-status-badge users-status-admin">Administrador Dueño</span>
                        @elseif($user['role'] == 1)
                            <span class="users-status-badge users-status-admin-trabajador">Administrador Trabajador</span>
                        @elseif($user['role'] == 2)
                            <span class="users-status-badge users-status-trabajador">Trabajador</span>
                        @endif
                    </td>
                    <td data-label="Acciones">
                        <div class="users-action-buttons">
                            <form action="{{ route('users.edit', $user['id']) }}" method="GET">
                                @csrf
                                <button type="submit" class="users-table-action-btn users-table-btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            @if($user['role'] != 0)
                            <button class="users-table-action-btn users-table-btn-delete users-delete-button" data-id="{{ $user['id'] }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            <div class="users-pagination-container">
                <div class="users-pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="users-pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('users.index', ['page' => 1, 'query' => request('query')]) }}" class="users-btn users-btn-primary users-btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('users.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="users-btn users-btn-primary users-btn-custom-size">Anterior</a>
                    @endif
                    
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="users-btn users-btn-primary active users-btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('users.index', ['page' => $i, 'query' => request('query')]) }}" class="users-btn users-btn-outline-primary users-btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <form method="GET" action="{{ route('users.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control users-page-input" placeholder="Ir a">
                        <button type="submit" class="users-btn users-btn-info users-btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('users.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="users-btn users-btn-primary users-btn-custom-size">Siguiente</a>
                        <a href="{{ route('users.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="users-btn users-btn-primary users-btn-custom-size">
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

    <!-- Modal de eliminación -->
    <div class="modal fade" id="users-delete-modal" tabindex="-1" aria-labelledby="users-delete-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content users-modal-content">
                <div class="modal-header users-modal-header">
                    <h5 class="modal-title users-modal-title" id="users-delete-modal-label">Confirmar Eliminación</h5>
                    <button type="button" class="close users-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="users-delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este usuario?</p>
                        <div class="form-group">
                            <label for="users-admin-password">Contraseña de Administrador Dueño</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="users-admin-password" name="admin_password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('users-admin-password', this)">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="users-btn users-btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="users-btn users-btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Variables para JavaScript -->
    <script>
        @if(session('success'))
            const successMessage = '{{ session('success') }}';
        @endif
        
        @if(session('error'))
            const errorMessage = '{{ session('error') }}';
        @endif
        
        window.usersLastPage = {{ $lastPage }};
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/users.js') }}"></script>
</body>
</html>
@endsection