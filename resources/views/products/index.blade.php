@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
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
        <h1 class="mb-4">Inventario General</h1>
        @if (session('role') === '1' || session('role') === '0')
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
                <a href="{{ route('products.index', ['download' => 'pdf']) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 10px;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        @endif
        <form method="GET" action="{{ route('products.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Productos..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($products) && count($products) > 0)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Ubicación</th>
                    <th>Imagen</th>
                    <th style="text-align: center;" colspan="3">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                    <td data-label="Nombre">{{ $product['name'] }}</td>
                    <td data-label="Descripción">{{ $product['description'] ?? 'N/A' }}</td>
                    <td data-label="Precio">{{ $product['price'] != 0 ? '$' . number_format($product['price'], 2, '.', ',') : 'N/A' }}</td>
                    <td data-label="Categoría">{{ $product['category']['name'] }}</td>
                    <td data-label="Proveedor">{{ $product['supplier']['company'] ?? 'N/A' }}</td>
                    <td data-label="Ubicación">{{ $product['location'] ?? 'N/A' }}</td>
                    <td data-label="Imagen">
                        <img src="{{ config('app.backend_api') }}/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen" width="100" style="border-radius: 10px;">
                    </td>
                    <td data-label="Acciones" style="text-align: center;">
                        <div class="btn-group btn-group-horizontal" role="group">
                            @if (session('role') === '1' || session('role') === '0')
                            <form action="{{ route('products.edit', $product['id']) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-custom-size">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="{{ route('products.destroy', $product['id']) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-custom-size"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                            <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info btn-custom-size" style="margin-right: 5px;">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                            <a href="{{ route('products.output.get', $product['id']) }}" class="btn btn-info btn-custom-size" style="margin-right: 5px;">
                                <i class="fas fa-sign-out-alt" style="color: red;"></i>
                            </a>
                            <a href="{{ route('products.loans.get', $product['id']) }}" class="btn btn-info btn-custom-size">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                        </div>
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('products.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                    <a href="{{ route('products.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-secondary {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($currentPage < $lastPage)
                    <a href="{{ route('products.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                <a href="{{ route('products.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            </div>
            @else
            <p>No se encontraron productos.</p>
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

    <script>
    const deleteForms = document.querySelectorAll('.delete-form'); // Selecciona todos los formularios de eliminar

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene la acción predeterminada del formulario
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar este producto?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, enviar el formulario
                    form.submit();
                }
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
