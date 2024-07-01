@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorías</title>
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
                    .pagination {
                display: flex;
                justify-content: flex-start;
                align-items: center;
            }

            .pagination span {
                margin: 0 10px;
            }

            .pagination a {
                margin: 0 5px; /* Ajusta este valor según sea necesario */
            }

            .pagination .active {
                font-weight: bold;
                text-decoration: underline;
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
        <h1 class="mb-4">Lista de Categorías</h1>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
        </div>
        <form method="GET" action="{{ route('categories.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Categorías..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($categories) && count($categories) > 0)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="text-align: center;">Nombre</th>
                    <th style="text-align: center;">Descripción</th>
                    <th style="text-align: center;" colspan="2">Acciones</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td data-label="Nombre" style="text-align: center;">{{ $category['name'] }}</td>
                            <td data-label="Descripción" style="text-align: center;">{{ $category['description'] ?? 'N/A' }}</td>
                            <td data-label="Acciones" style="text-align: center;">
                                <div class="btn-group btn-group-horizontal" role="group">
                                    <form action="{{ route('categories.edit', $category['id']) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-custom-size">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('categories.destroy', $category['id']) }}" method="POST" class="delete-form">
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
                    <a href="{{ route('categories.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                <span>Página {{ $currentPage }} de {{ $lastPage }}</span>
                @if($currentPage < $lastPage)
                    <a href="{{ route('categories.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                @if($currentPage > 1)
                    <a href="{{ route('categories.index') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left"></i> Inicio
                    </a>
                @endif
            </div>            
            @else
            <p>No se encontraron categorías.</p>
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
    const deleteForms = document.querySelectorAll('.delete-form'); // Selecciona todos los formularios de eliminar

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene la acción predeterminada del formulario
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar esta categoría?",
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

    <!-- JavaScript para Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
