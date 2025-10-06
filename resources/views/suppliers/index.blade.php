@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/suppliers.css') }}" rel="stylesheet">
</head>
<body class="suppliers-container">
    <div class="suppliers-content">
        <h1 class="suppliers-title">Lista de Proveedores</h1>
        
        <div class="d-flex justify-content-between align-items-center suppliers-page-header mb-3">
            @if (session('role') === '1' || session('role') === '0')
            <a href="{{ route('suppliers.create') }}" class="suppliers-btn suppliers-btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
            @endif
            
            <div class="suppliers-export-buttons">
                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="suppliers-btn suppliers-btn-danger suppliers-pdf-download">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['download' => 'excel'])) }}" class="suppliers-btn suppliers-btn-success suppliers-excel-download">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        
        <div class="suppliers-search-container">
            <form method="GET" action="{{ route('suppliers.index') }}">
                <div class="suppliers-input-group">
                    <input type="text" name="query" class="suppliers-form-control form-control" placeholder="Buscar Proveedores..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="suppliers-btn" type="submit" style="background: #000000ff; color: white;">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="suppliers-table-container">
            @if(isset($suppliers) && count($suppliers) > 0)
            <table class="suppliers-table">
                <thead>
                <tr>
                    <th>Precio</th>
                    <th>Empresa</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    @if (session('role') === '1' || session('role') === '0')
                    <th>Acciones</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                             <td data-label="Empresa">{{ $supplier['company'] }}</td>
                            <td data-label="Teléfono">{{ $supplier['phone'] }}</td>
                            <td data-label="Email">{{ $supplier['email'] ?? 'N/A'}}</td>
                            <td data-label="Dirección">{{ $supplier['address'] ?? 'N/A' }}</td>
                            @if (session('role') === '1' || session('role') === '0')
                            <td data-label="Acciones">
                                <div class="suppliers-action-buttons">
                                    <form action="{{ route('suppliers.edit', $supplier['id']) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="suppliers-table-action-btn suppliers-table-btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('suppliers.destroy', $supplier['id']) }}" method="POST" class="suppliers-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="suppliers-table-action-btn suppliers-table-btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="suppliers-pagination-container">
                <div class="suppliers-pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="suppliers-pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('suppliers.index', ['page' => 1, 'query' => request('query')]) }}" class="suppliers-btn suppliers-btn-primary suppliers-btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('suppliers.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="suppliers-btn suppliers-btn-primary suppliers-btn-custom-size">Anterior</a>
                    @endif
                    
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="suppliers-btn suppliers-btn-primary active suppliers-btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('suppliers.index', ['page' => $i, 'query' => request('query')]) }}" class="suppliers-btn suppliers-btn-outline-primary suppliers-btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <form method="GET" action="{{ route('suppliers.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control suppliers-page-input" placeholder="Ir a">
                        <button type="submit" class="suppliers-btn suppliers-btn-info suppliers-btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('suppliers.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="suppliers-btn suppliers-btn-primary suppliers-btn-custom-size">Siguiente</a>
                        <a href="{{ route('suppliers.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="suppliers-btn suppliers-btn-primary suppliers-btn-custom-size">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @else
            <p class="text-center py-4">No se encontraron proveedores.</p>
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
        
        window.suppliersLastPage = {{ $lastPage }};
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/suppliers.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection