@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Salidas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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

            .table {
                width: 100%;
                table-layout: fixed; /* Asegura que la tabla no exceda el ancho del contenedor */
            }
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
        <h1 class="mb-4">Listado de Salidas</h1>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div class="btn-group-horizontal mb-3">
                <a href="{{ route('outputs.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('outputs.index', array_merge(request()->query(), ['download' => 'month_pdf'])) }}" class="btn btn-danger btn-custom-size">
                    <i class="fas fa-file-pdf"></i> PDF del Mes
                </a>
            </div>
            @if(isset($monthData))
                <div class="ml-auto text-left mb-3">
                    <p class="mb-0">Cantidad de salidas del mes actual: {{ number_format($monthData['count'], 0, ',', '.') }}</p>
                </div>
            @else
                <p class="mb-10">No hay datos disponibles.</p>
            @endif
        </div>
        
        <form method="GET" action="{{ route('outputs.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar Salidas..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
        <form method="GET" action="{{ route('outputs.index') }}" class="form-inline mb-3">
            <div class="input-group">
                <input type="text" name="start_date" placeholder="Fecha Inicio" class="form-control datepicker mr-2" required>
                <input type="text" name="end_date" placeholder="Fecha Fin" class="form-control datepicker mr-2" required>
                <div class="btn-group" role="group">
                    <button type="submit" name="download" value="between_dates_pdf" class="btn btn-danger btn-custom-size">
                        <i class="fas fa-file-pdf"></i> PDF por Fechas
                    </button>
                    <button type="submit" name="download" value="between_dates_excel" class="btn btn-success btn-custom-size">
                        <i class="fas fa-file-excel"></i> Excel por Fechas
                    </button>
                    <button type="button" id="clear-dates" class="btn btn-secondary btn-custom-size">
                        <i class="fas fa-eraser"></i> Limpiar Fechas
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($outputs) && count($outputs) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Ubicación</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Nombre Cuenta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outputs as $output)
                    <tr>
                        <td data-label="Proyecto">{{ $output['project']['name'] ?? 'N/A' }}</td>
                        <td data-label="Producto">{{ $output['product']['name'] ?? 'N/A' }}</td>
                        <td data-label="Responsable">{{ $output['responsible'] ?? 'N/A' }}</td>
                        <td data-label="Cantidad">{{ number_format($output['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                        <td data-label="Ubicación">{{ $output['product']['location'] ?? 'N/A' }}</td>
                        <td data-label="Descripción">{{ $output['description'] ?? 'N/A' }}</td>
                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($output['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}</td>
                        <td data-label="Nombre Cuenta">{{ $output['user']['name'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('outputs.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
            
                <span>Página {{ $currentPage }} de {{ $lastPage }}</span>
            
                @if($currentPage < $lastPage)
                    <a href="{{ route('outputs.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
            
                @if($currentPage > 1)
                    <a href="{{ route('outputs.index') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left"></i> Inicio
                    </a>
                @endif
            </div>            
            @else
            <p>No se encontraron salidas.</p>
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
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
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

            $('#clear-dates').on('click', function() {
                $('input[name="start_date"], input[name="end_date"]').val('');
            });
        });
    </script>
</body>
</html>
@endsection
