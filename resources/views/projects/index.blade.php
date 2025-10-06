@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proyectos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/projects.css') }}" rel="stylesheet">
</head>
<body class="projects-container">
    <div class="projects-content">
        <h1 class="projects-title">Lista de Proyectos</h1>
        
        <div class="d-flex justify-content-between align-items-center projects-page-header mb-3">
            <a href="{{ route('projects.create') }}" class="projects-btn projects-btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
            
            <div class="projects-export-buttons">
                <a href="{{ route('projects.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="projects-btn projects-btn-danger projects-pdf-download">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('projects.index', array_merge(request()->query(), ['download' => 'excel'])) }}" class="projects-btn projects-btn-success projects-excel-download">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        
        <div class="projects-search-container">
            <form method="GET" action="{{ route('projects.index') }}">
                <div class="projects-input-group">
                    <input type="text" name="query" class="projects-form-control form-control" placeholder="Buscar Proyectos..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="projects-btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="projects-table-container">
            @if(isset($projects) && count($projects) > 0)
            <table class="projects-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Nombre de la Empresa</th>
                        <th>RFC</th>
                        <th>Dirección</th>
                        <th>Ubicación</th> <!-- NUEVO CAMPO -->
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
                        <td data-label="Descripción">{{ $project['description'] ?? 'N/A' }}</td>
                        <td data-label="Nombre de la Empresa">{{ $project['company_name'] }}</td>
                        <td data-label="RFC">{{ $project['rfc'] ?? 'N/A' }}</td>
                        <td data-label="Dirección">{{ $project['address'] }}</td>
                        <td data-label="Ubicación">{{ $project['ubicacion'] ?? 'N/A' }}</td> <!-- NUEVO CAMPO -->
                        <td data-label="Teléfono">{{ $project['phone_number'] }}</td>
                        <td data-label="Email">{{ $project['email'] }}</td>
                        <td data-label="Nombre del Cliente">{{ $project['client_name'] }}</td>
                        <td data-label="Acciones">
                            <div class="projects-action-buttons">
                                <form action="{{ route('projects.edit', $project['id']) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="projects-table-action-btn projects-table-btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form action="{{ route('projects.destroy', $project['id']) }}" method="POST" class="projects-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="projects-table-action-btn projects-table-btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="projects-pagination-container">
                <div class="projects-pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="projects-pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('projects.index', ['page' => 1, 'query' => request('query')]) }}" class="projects-btn projects-btn-primary projects-btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('projects.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="projects-btn projects-btn-primary projects-btn-custom-size">Anterior</a>
                    @endif
                    
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="projects-btn projects-btn-primary active projects-btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('projects.index', ['page' => $i, 'query' => request('query')]) }}" class="projects-btn projects-btn-outline-primary projects-btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <form method="GET" action="{{ route('projects.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control projects-page-input" placeholder="Ir a">
                        <button type="submit" class="projects-btn projects-btn-info projects-btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('projects.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="projects-btn projects-btn-primary projects-btn-custom-size">Siguiente</a>
                        <a href="{{ route('projects.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="projects-btn projects-btn-primary projects-btn-custom-size">
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

    <!-- Variables para JavaScript -->
    <script>
        @if(session('success'))
            const successMessage = '{{ session('success') }}';
        @endif
        
        @if(session('error'))
            const errorMessage = '{{ session('error') }}';
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/projects.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection