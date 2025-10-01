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

        .export-buttons {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .export-buttons {
                width: 100%;
                justify-content: space-between;
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
        <h1>Lista de Proyectos</h1>
        
        <div class="d-flex justify-content-between align-items-center page-header mb-3">
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
            
            <div class="export-buttons">
                <a href="{{ route('projects.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('projects.index', array_merge(request()->query(), ['download' => 'excel'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        
        <div class="search-container">
            <form method="GET" action="{{ route('projects.index') }}">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Buscar Proyectos..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="table-container">
            @if(isset($projects) && count($projects) > 0)
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubicación / sub ubicacion</th>
                        <th>Nombre de la Empresa</th>
                        <th>RFC</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Nombre del Cliente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td data-label="Nombre">{{ $project['name'] }}</td>
                        <td data-label="Ubicación Física">{{ $project['description'] ?? 'N/A' }}</td>
                        <td data-label="Nombre de la Empresa">{{ $project['company_name'] }}</td>
                        <td data-label="RFC">{{ $project['rfc'] ?? 'N/A' }}</td>
                        <td data-label="Dirección">{{ $project['address'] }}</td>
                        <td data-label="Teléfono">{{ $project['phone_number'] }}</td>
                        <td data-label="Email">{{ $project['email'] }}</td>
                        <td data-label="Nombre del Cliente">{{ $project['client_name'] }}</td>
                        <td data-label="Acciones">
                            <div class="action-buttons">
                                <form action="{{ route('projects.edit', $project['id']) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="table-action-btn table-btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form action="{{ route('projects.destroy', $project['id']) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-action-btn table-btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
                        <a href="{{ route('projects.index', ['page' => 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('projects.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Anterior</a>
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
                            <a href="{{ route('projects.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-outline-primary btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <!-- Saltar a página específica -->
                    <form method="GET" action="{{ route('projects.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control page-input" placeholder="Ir a">
                        <button type="submit" class="btn btn-info btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('projects.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Siguiente</a>
                        <a href="{{ route('projects.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @else
            <p class="text-center py-4">No se encontraron proyectos.</p>
            @endif
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

    <!-- JavaScript para la ventana emergente de confirmación de eliminación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar este proyecto?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000ff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#f7fafc'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
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
    </script>

    <!-- JavaScript for Bootstrap and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@endsection