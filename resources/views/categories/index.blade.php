@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorías</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/categories.css') }}" rel="stylesheet">
</head>
<body class="categories-container">
    <div class="categories-content">
        <h1 class="categories-title">Lista de Categorías</h1>
        
        <div class="d-flex justify-content-between align-items-center categories-page-header mb-3">
            <a href="{{ route('categories.create') }}" class="categories-btn categories-btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
        </div>
      
        <div class="categories-search-container">
            <form method="GET" action="{{ route('categories.index') }}">
                <div class="categories-input-group">
                    <input type="text" name="query" class="categories-form-control form-control" placeholder="Buscar Categorías..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="categories-btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="categories-table-container">
            @if(isset($categories) && count($categories) > 0)
            <table class="categories-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Materiales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td data-label="Nombre">{{ $category['name'] }}</td>
                            <td data-label="Descripción">{{ $category['description'] ?? 'N/A' }}</td>
                            <td data-label="Materiales">{{ $category['materials'] ?? 'N/A' }}</td>
                            <td data-label="Acciones">
                                <div class="categories-action-buttons">
                                    <a href="{{ route('categories.edit', $category['id']) }}" class="categories-table-action-btn categories-table-btn-edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('categories.destroy', $category['id']) }}" method="POST" class="categories-delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="categories-table-action-btn categories-table-btn-delete">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="categories-pagination-container">
                <div class="categories-pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="categories-pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('categories.index', ['page' => 1, 'query' => request('query')]) }}" class="categories-btn categories-btn-primary categories-btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('categories.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="categories-btn categories-btn-primary categories-btn-custom-size">Anterior</a>
                    @endif
                    
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="categories-btn categories-btn-primary active categories-btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('categories.index', ['page' => $i, 'query' => request('query')]) }}" class="categories-btn categories-btn-outline-primary categories-btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <form method="GET" action="{{ route('categories.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control categories-page-input" placeholder="Ir a">
                        <button type="submit" class="categories-btn categories-btn-info categories-btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('categories.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="categories-btn categories-btn-primary categories-btn-custom-size">Siguiente</a>
                        <a href="{{ route('categories.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="categories-btn categories-btn-primary categories-btn-custom-size">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @else
            <p class="text-center py-4">No se encontraron categorías.</p>
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
    <script src="{{ asset('js/categories.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection