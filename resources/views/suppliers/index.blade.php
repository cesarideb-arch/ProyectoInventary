@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
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
        <h1 class="mb-4">Lista de Proveedores</h1>
        <div class="d-flex justify-content-between mb-3">
            @if (session('role') === '1' || session('role') === '0')
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-custom-size">Agregar</a>
            @endif
            <div class="btn-group-horizontal">
                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 10px;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['download' => 'excel'])) }}" class="btn btn-success btn-custom-size" style="background-color: green; border-radius: 10px;">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('suppliers.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Proveedores..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($suppliers) && count($suppliers) > 0)
            <table class="table table-striped">
                <thead>
                <tr>
                    <th style="text-align: center;">Artículo</th>
                    <th style="text-align: center;">Precio</th>
                    <th style="text-align: center;">Empresa</th>
                    <th style="text-align: center;">Teléfono</th>
                    <th style="text-align: center;">Email</th>
                    <th style="text-align: center;">Dirección</th>
                    @if (session('role') === '1' || session('role') === '0')
                    <th style="text-align: center;" colspan="2">Acciones</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td data-label="Artículo" style="text-align: center;">{{ $supplier['article'] }}</td>
                            <td data-label="Precio" style="text-align: center;">${{ number_format($supplier['price'], 2, '.', ',') }}</td>
                            <td data-label="Empresa" style="text-align: center;">{{ $supplier['company'] }}</td>
                            <td data-label="Teléfono" style="text-align: center;">{{ $supplier['phone'] }}</td>
                            <td data-label="Email" style="text-align: center;">{{ $supplier['email'] ?? 'N/A'}}</td>
                            <td data-label="Dirección" style="text-align: center;">{{ $supplier['address'] ?? 'N/A' }}</td>
                            <td data-label="Acciones" style="text-align: center;">
                                <div class="btn-group btn-group-horizontal" role="group">
                                    @if (session('role') === '1' || session('role') === '0')
                                    <form action="{{ route('suppliers.edit', $supplier['id']) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-custom-size">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('suppliers.destroy', $supplier['id']) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-custom-size"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('suppliers.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                <span>Página {{ $currentPage }} de {{ $lastPage }}</span>
                @if($currentPage < $lastPage)
                    <a href="{{ route('suppliers.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                @if($currentPage > 1)
                    <a href="{{ route('suppliers.index') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left"></i> Inicio
                    </a>
                @endif
            </div>
            
            </div>
            @else
            <p>No se encontraron proveedores.</p>
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
    const deleteForms = document.querySelectorAll('.delete-form'); // Select all delete forms

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form action
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar este proveedor?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    form.submit();
                }
            });
        });
    });
    </script>

    <!-- JavaScript for Bootstrap and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
