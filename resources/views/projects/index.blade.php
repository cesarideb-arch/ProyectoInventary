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
    <style>
        body {
            overflow-x: hidden; /* Oculta las barras de desplazamiento horizontales */
        }

        .container {
            max-width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        .table-responsive {
            overflow-x: auto; /* Permite la barra de desplazamiento solo en la tabla */
        }

        .btn-group-horizontal {
            display: flex;
            justify-content: center; /* Center buttons */
            align-items: center;
        }

        .btn-group-horizontal .btn {
            margin-left: 5px; /* Adds space between buttons */
        }

        .pagination {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .pagination span {
            margin: 0 10px;
        }

        .pagination a {
            margin: 0 5px; /* Adjust as necessary */
        }

        .pagination .active {
            font-weight: bold;
            text-decoration: underline;
        }

        .btn-custom-size {
            padding: 6px 12px;
        }

        .btn-danger {
            background-color: #ff0000; /* Red color */
            border-color: #ff0000; /* Red color */
            color: #fff; /* White text */
        }

        .btn-danger:hover {
            background-color: #cc0000; /* Darker red on hover */
            border-color: #cc0000; /* Darker red on hover */
        }

        .table td, .table th {
            text-align: center; /* Center text in cells */
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
                text-align: left; /* Align text to the left for mobile */
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
        <h1 class="mb-4">Lista de Proyectos</h1>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
            <div class="btn-group-horizontal">
                <a href="{{ route('projects.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 10px;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('projects.index', array_merge(request()->query(), ['download' => 'excel'])) }}" class="btn btn-success btn-custom-size" style="background-color: green; border-radius: 10px;">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('projects.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Proyectos..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
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
                        <td data-label="Nombre">{{ $project['name'] }}</td>
                        <td data-label="Descripción">{{ $project['description'] ?? 'N/A' }}</td>
                        <td data-label="Nombre de la Empresa">{{ $project['company_name'] }}</td>
                        <td data-label="RFC">{{ $project['rfc'] ?? 'N/A' }}</td>
                        <td data-label="Dirección">{{ $project['address'] }}</td>
                        <td data-label="Teléfono">{{ $project['phone_number'] }}</td>
                        <td data-label="Email">{{ $project['email'] }}</td>
                        <td data-label="Nombre del Cliente">{{ $project['client_name'] }}</td>
                        <td data-label="Acciones">
                            <div class="btn-group btn-group-horizontal text-center" role="group">
                                <form action="{{ route('projects.edit', $project['id']) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-custom-size">
                                        <i class="fas fa-edit"></i>
                                    </button>
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
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('projects.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
            
                <span>Página {{ $currentPage }} de {{ $lastPage }}</span>
            
                @if($currentPage < $lastPage)
                    <a href="{{ route('projects.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
            
                @if($currentPage > 1)
                    <a href="{{ route('projects.index') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left"></i> Inicio
                    </a>
                @endif
            </div>     
            @else
            <p>No se encontraron proyectos.</p>
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
