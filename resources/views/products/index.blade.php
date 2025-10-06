@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario General</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/products.css') }}" rel="stylesheet">
</head>
<body class="products-container">
    <div class="products-content">
        <h1 class="products-title">Inventario General</h1>
        
        <div class="d-flex justify-content-between align-items-center products-page-header mb-3">
            @if (session('role') === '1' || session('role') === '0')
            <a href="{{ route('products.create') }}" class="products-btn products-btn-primary">
                <i class="fas fa-plus"></i> Agregar
            </a>
            @endif
            
            <div class="products-export-buttons">
                <a href="{{ route('products.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="products-btn products-btn-danger products-pdf-download">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('products.index', array_merge(request()->query(), ['download' => 'excel'])) }}" class="products-btn products-btn-success products-excel-download">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        
        <div class="products-search-container">
            <form method="GET" action="{{ route('products.index') }}" id="searchForm">
                <div class="products-input-group">
                    <input type="text" name="query" class="products-form-control form-control" placeholder="Buscar Productos..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="products-btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="products-table-container">
            @if(isset($products) && count($products) > 0)
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Préstamos</th>
                        <th>Fecha de Agregado</th>
                        <th>Imagen</th>
                        @if (session('role') === '1' || session('role') === '0')
                        <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td data-label="Nombre">
                            <span class="products-toggle-details" data-id="{{ $product['id'] }}">
                                <i class="fas fa-chevron-down"></i> {{ $product['name'] }}
                            </span>
                        </td>
                        <td data-label="Cantidad">{{ number_format($product['quantity'] ?? 0, 0, '.', ',') }}</td>
                        <td data-label="Préstamos">{{ $product['loans_count'] ?? 0 }}</td>
                        <td data-label="Fecha de Agregado">
                            @if(isset($product['created_at']))
                                {{ \Carbon\Carbon::parse($product['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td data-label="Imagen">
                            <img src="{{ config('app.backend_api') }}/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen" class="products-product-image">
                        </td>
                        @if (session('role') === '1' || session('role') === '0')
                        <td data-label="Acciones">
                            <div class="products-action-buttons">
                                <form action="{{ route('products.edit', $product['id']) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="products-table-action-btn products-table-btn-edit">
                                        <i class="fas fa-edit"></i>
                                        <span class="products-tooltip-text">Editar</span>
                                    </button>
                                </form>
                                <form action="{{ route('products.destroy', $product['id']) }}" method="POST" class="products-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="products-table-action-btn products-table-btn-delete">
                                        <i class="fas fa-trash"></i>
                                        <span class="products-tooltip-text">Eliminar</span>
                                    </button>
                                </form>
                                <a href="{{ route('products.show', $product['id']) }}" class="products-table-action-btn products-table-btn-info">
                                    <i class="fas fa-arrow-circle-right"></i>
                                    <span class="products-tooltip-text">entradas</span>
                                </a>
                                <a href="{{ route('products.output.get', $product['id']) }}" class="products-table-action-btn products-table-btn-output">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span class="products-tooltip-text">Salidas</span>
                                </a>
                                <a href="{{ route('products.loans.get', $product['id']) }}" class="products-table-action-btn products-table-btn-loan">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span class="products-tooltip-text">Préstamos</span>
                                </a>
                            </div>
                        </td>
                        @endif
                    </tr>
                    <tr class="products-expandable-row" id="products-details-{{ $product['id'] }}">
                        <td colspan="@if (session('role') === '1' || session('role') === '0') 6 @else 5 @endif">
                            <div class="products-expandable-content">
                                <h5>Detalles del Producto</h5>
                                <table class="products-details-table">
                                    <tr>
                                        <td class="products-details-label">Descripción:</td>
                                        <td>{{ $product['description'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Marca:</td>
                                        <td>{{ $product['brand'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Modelo:</td>
                                        <td>{{ $product['model'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Precio:</td>
                                        <td>{{ $product['price'] != 0 ? '$' . number_format($product['price'], 2, '.', ',') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Observaciones:</td>
                                        <td>{{ $product['observations'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Categoría:</td>
                                        <td>{{ $product['category']['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Proveedor:</td>
                                        <td>{{ $product['supplier']['company'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="products-details-label">Ubicación:</td>
                                        <td>{{ $product['location'] ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            <div class="products-pagination-container">
                <div class="products-pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="products-pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('products.index', ['page' => 1, 'query' => request('query')]) }}" class="products-btn products-btn-primary products-btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('products.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="products-btn products-btn-primary products-btn-custom-size">Anterior</a>
                    @endif
                    
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="products-btn products-btn-primary active products-btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('products.index', ['page' => $i, 'query' => request('query')]) }}" class="products-btn products-btn-outline-primary products-btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <form method="GET" action="{{ route('products.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control products-page-input" placeholder="Ir a">
                        <button type="submit" class="products-btn products-btn-info products-btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('products.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="products-btn products-btn-primary products-btn-custom-size">Siguiente</a>
                        <a href="{{ route('products.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="products-btn products-btn-primary products-btn-custom-size">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @else
            <p class="text-center py-4">No se encontraron productos.</p>
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
        
        // Variables para la descarga
        const currentQuery = '{{ request('query') }}';
        const currentPage = '{{ request('page') }}';
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/products.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection