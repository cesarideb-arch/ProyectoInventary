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
        /* Estilos para la ventana emergente */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            text-align: center;
        }

        .modal-content i {
            color: red;
            font-size: 24px;
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-buttons button {
            margin: 0 10px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-buttons button.cancel {
            background-color: #ccc;
        }

        .modal-buttons button.confirm {
            background-color: #ff3b30;
            color: white;
        }

        .btn-group-horizontal {
            display: flex;
            align-items: center;
        }

        .btn-group-horizontal .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Inventario General</h1>
        @if (session('role') === '1' || session('role') === '0')
            <div class="mb-3">
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
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
            <!-- Tabla de productos -->
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
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['description'] ?? 'N/A' }}</td>
                    <td>${{ number_format($product['price'], 2, '.', ',') }}</td>
                    <td>{{ $product['category']['name']}}</td>
                    <td>{{ $product['supplier']['company'] ?? 'N/A' }}</td>
                    <td>{{ $product['location'] ?? 'N/A' }}</td>
                    <td>
                        <img src="{{ config('app.backend_api') }}/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen" width="100" style="border-radius: 10px;">
                    </td>
                    <td>
                        <div class="btn-group btn-group-horizontal" role="group">
                            <!-- Botón de Edición -->
                            @if (session('role') === '1' || session('role') === '0')
                            <form action="{{ route('products.edit', $product['id']) }}" method="GET" style="margin-right: 5px;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <!-- Botón de Eliminación -->
                            <form action="{{ route('products.destroy', $product['id']) }}" method="POST" class="delete-form" style="margin-right: 5px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endif
                            <!-- Botón adicional, por ejemplo, Entradas -->
                            <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info btn-sm" style="margin-right: 5px;">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                            <!-- Botón de Salida -->
                            <a href="{{ route('products.output.get', $product['id']) }}" class="btn btn-info btn-sm" style="margin-right: 5px;">
                                <i class="fas fa-sign-out-alt" style="color: red;"></i>
                            </a>
                            <!-- Botón de Préstamos -->
                            <a href="{{ route('products.loans.get', $product['id']) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                            <div class="mb-3">
                                <a href="{{ route('products.index', ['download' => 'pdf']) }}" class="btn btn-success">Descargar PDF</a>
                            </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
