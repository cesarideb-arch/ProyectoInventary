@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Entradas</title>
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

        .btn-custom-size {
    margin-right: 10px; /* Añade espacio a la derecha de cada botón excepto el último */
}

.btn-custom-size:last-child {
    margin-right: 0; /* Remueve el margen del último botón para que no tenga espacio extra a la derecha */
}
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Listado de Entradas</h1>
        <div class="d-flex justify-content-between mb-3">
            {{-- <a href="{{ route('entrances.create') }}" class="btn btn-primary btn-custom-size">Agregar</a> --}}
            <div style="display: flex; justify-content: flex-end;">
                <a href="{{ route('entrances.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('entrances.index', array_merge(request()->query(), ['download' => 'month_pdf'])) }}" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF del Mes
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('entrances.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Entradas..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($entrances) && count($entrances) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entrances as $entrance)
                    <tr>
                        <td>{{ $entrance['project']['name'] ?? 'N/A' }}</td>
                        <td>{{ $entrance['product']['name'] ?? 'N/A' }}</td>
                        <td>{{ $entrance['responsible'] ?? 'N/A' }}</td>
                        <td>{{ number_format($entrance['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                        <td>{{ $entrance['description'] ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($entrance['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('entrances.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                    <a href="{{ route('entrances.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-secondary {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($currentPage < $lastPage)
                    <a href="{{ route('entrances.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                <a href="{{ route('entrances.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            @else
            <p>No se encontraron entradas.</p>
            @endif
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
@endsection
