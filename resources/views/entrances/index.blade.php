@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Entradas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
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
            flex-wrap: wrap;
        }

        .btn-group-horizontal .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .btn-custom-size {
            padding: 6px 12px;
        }

        .btn-danger {
            background-color: #ff0000;
            border-color: #ff0000;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #cc0000;
            border-color: #cc0000;
        }

        .btn-custom-size {
            margin-right: 10px;
        }

        .btn-custom-size:last-child {
            margin-right: 0;
        }

        .form-inline .form-control {
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }

        .form-inline .btn {
            display: inline-block;
            vertical-align: middle;
        }

        .table td, .table th {
            text-align: center; /* Centrando el texto de las celdas */
        }

        @media (max-width: 576px) {
            .ml-auto {
                width: 100%;
                text-align: left;
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
                text-align: left; /* Alinea el texto a la izquierda para la versión móvil */
            }
            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
                margin-right: 10px;
            }
            .input-group, .form-inline .input-group {
                flex-direction: row;
                align-items: stretch;
                width: 100%;
            }
            .form-inline .input-group .form-control, .form-inline .input-group .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            .form-inline .input-group .btn {
                margin-bottom: 0;
            }
            .form-inline .input-group .input-group-append {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Listado de Entradas</h1>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div class="btn-group-horizontal mb-3">
                <a href="{{ route('entrances.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('entrances.index', array_merge(request()->query(), ['download' => 'month_pdf'])) }}" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF del Mes
                </a>
            </div>
            @if(isset($monthData))
                <div class="ml-auto text-left mb-3">
                    <p class="mb-0">Conteo de entradas del mes actual: {{ number_format($monthData['count'], 0, ',', '.') }}</p>
                </div>
            @else
                <p class="mb-10">No hay datos disponibles.</p>
            @endif
        </div>
        
        <form method="GET" action="{{ route('entrances.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Entradas..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
        <form method="GET" action="{{ route('entrances.index') }}" class="form-inline mb-3">
            <input type="hidden" name="download" value="between_dates_pdf">
            <div class="input-group">
                <input type="text" name="start_date" placeholder="Fecha Inicio" class="form-control datepicker mr-2" required>
                <input type="text" name="end_date" placeholder="Fecha Fin" class="form-control datepicker mr-2" required>
                <button type="submit" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF por Fechas
                </button>
                <button type="submit" class="btn btn-success btn-custom-size">
                    <i class="fas fa-file-excel"></i> Excel por Fechas
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
                        <th>Precio</th>
                        <th>Gastado</th>
                        <th>Ubicación</th>
                        <th>Descripción</th>
                        <th>Folio</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entrances as $entrance)
                    <tr>
                        <td data-label="Proyecto">{{ $entrance['project']['name'] ?? 'N/A' }}</td>
                        <td data-label="Producto">{{ $entrance['product']['name'] ?? 'N/A' }}</td>
                        <td data-label="Responsable">{{ $entrance['responsible'] ?? 'N/A' }}</td>
                        <td data-label="Cantidad">{{ number_format($entrance['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                        <td data-label="Precio">{{ number_format($entrance['price'] ?? 'N/A', 2, '.', ',') }}</td>
                        <td data-label="Gastado">{{ number_format(($entrance['price'] ?? 0) * ($entrance['quantity'] ?? 0), 2, '.', ',') }}</td>
                        <td data-label="Ubicación">{{ $entrance['product']['location'] ?? 'N/A' }}</td>
                        <td data-label="Descripción">{{ $entrance['description'] ?? 'N/A' }}</td>
                        <td data-label="Folio">{{ $entrance['folio'] ?? 'N/A' }}</td>                        
                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($entrance['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}</td>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
    <script>
        $(document).ready(function(){
            $.fn.datepicker.dates['es'] = {
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
                months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                today: "Hoy",
                clear: "Borrar",
                format: "dd/mm/yyyy",
                titleFormat: "MM yyyy",
                weekStart: 1
            };

            $('input[name="start_date"], input[name="end_date"]').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'es'
            });
        });
    </script>
</body>
</html>
@endsection
