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
        /* ESTILOS UNIFICADOS */
        :root {
            --primary: #000000ff;
            --secondary: #000000ff;
            --success: #38c172;
            --danger: #e3342f;
            --warning: #f6993f;
            --info: #000000ff;
            --light: #f8f9fa;
            --dark: #000000ff;
        }
        
        body {
            overflow-x: hidden;
            background-color: #f5f7f9;
            font-family: 'Nunito', sans-serif;
        }

        .container {
            max-width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .page-title {
            padding: 15px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .page-title h1 {
            font-size: 28px;
            color: var(--dark);
            font-weight: 700;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--secondary);
            color: white;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-info {
            background: var(--info);
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .btn-custom-size {
            padding: 0.5rem 1rem;
        }

        .search-container {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .input-group {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        
        .form-control {
            border: 1px solid #ddd;
            padding: 10px 15px;
            height: auto;
        }
        
        .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background: var(--dark);
            color: white;
        }
        
        .pagination {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 15px;
            background: var(--light);
            border-radius: 8px;
        }

        .pagination span {
            margin: 0 10px;
        }

        .pagination a {
            margin: 0 5px;
        }
        
        .pagination .active {
            font-weight: bold;
            background: var(--primary);
            color: white;
        }

        .pagination .page-input {
            width: 60px;
            display: inline-block;
            margin: 0 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 8px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .btn-edit {
            background: var(--info);
            color: white;
        }
        
        .btn-delete {
            background: var(--danger);
            color: white;
        }

        /* ESTILOS PARA LA TABLA CON CABECERA FIJA */
        .table-container {
            overflow-x: auto;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            max-height: 500px; /* Limitamos la altura para activar scroll */
            overflow-y: auto; /* Habilitamos scroll vertical */
            position: relative; /* Necesario para el sticky header */
        }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 14px;
        }
        
        .custom-table thead {
            background-color: #000000ff;
            position: sticky; /* Hacemos el header sticky */
            top: 0; /* Lo fijamos en la parte superior del contenedor */
            z-index: 10; /* Aseguramos que esté por encima del contenido */
        }
        
        .custom-table th {
            padding: 12px 15px;
            text-align: left;
            color: white;
            font-weight: 600;
            border-bottom: 2px solid #000000ff;
            position: sticky; /* También aplicamos sticky a cada th */
            top: 0;
            background-color: #000000ff; /* Mantenemos el color de fondo */
            z-index: 11; /* Aseguramos que esté por encima del thead */
        }
        
        .custom-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        
        .custom-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .custom-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .custom-table tbody tr:hover {
            background-color: #e9ecef;
        }

        @media (max-width: 576px) {
            .table-container {
                overflow-x: auto;
                max-height: none; /* En móviles quitamos la altura fija para la vista de tarjetas */
            }
            
            .custom-table thead {
                display: none; /* Ocultamos el header en móviles */
            }
            
            .custom-table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
            }
            
            .custom-table td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                border: none;
                border-bottom: 1px solid #eee;
            }
            
            .custom-table td:last-child {
                border-bottom: none;
            }
            
            .custom-table td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 10px;
                color: var(--dark);
            }
            
            .pagination {
                justify-content: center;
            }
            
            .pagination .page-input {
                width: 50px;
            }
            
            .action-buttons {
                justify-content: center;
                width: 100%;
            }
        }
        
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            background: var(--primary);
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        .modal-title {
            font-weight: 700;
        }
        
        .close {
            color: white;
            opacity: 0.8;
        }
        
        .close:hover {
            color: white;
            opacity: 1;
        }

        /* ESTILOS ESPECÍFICOS PARA LA PÁGINA DE SALIDAS */
        .stats-container {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }
        
        .stats-text {
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .date-filter-form {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .date-input-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        
        .date-input {
            flex: 1;
            min-width: 150px;
        }
        
        .date-filter-buttons {
            display: flex;
            gap: 10px;
        }
        
        @media (max-width: 768px) {
            .date-input-group {
                flex-direction: column;
                align-items: stretch;
            }
            
            .date-filter-buttons {
                width: 100%;
                justify-content: space-between;
            }
            
            .date-filter-buttons .btn {
                flex: 1;
            }
            
            /* Ajustes para móviles en la tabla */
            .table-container {
                max-height: 400px; /* Reducimos la altura máxima en móviles */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-title">
            <h1>Salidas</h1>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <div class="btn-group-horizontal mb-3">
                <a href="{{ route('outputs.index', array_merge(request()->query(), ['download' => 'month_pdf'])) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF del Mes
                </a>
            </div>
            
            @if(isset($monthData))
                <div class="stats-container">
                    <p class="stats-text">Cantidad de salidas del mes actual: {{ number_format($monthData['count'], 0, ',', '.') }}</p>
                </div>
            @else
                <p>No hay datos disponibles.</p>
            @endif
        </div>
        
        <div class="search-container">
            <form method="GET" action="{{ route('outputs.index') }}">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Buscar Salidas..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="date-filter-form">
            <form method="GET" action="{{ route('outputs.index') }}">
                <div class="date-input-group">
                    <div class="date-input">
                        <input type="text" name="start_date" placeholder="Fecha Inicio" class="form-control datepicker" required>
                    </div>
                    <div class="date-input">
                        <input type="text" name="end_date" placeholder="Fecha Fin" class="form-control datepicker" required>
                    </div>
                    <div class="date-filter-buttons">
                        <button type="submit" name="download" value="between_dates_pdf" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <button type="submit" name="download" value="between_dates_excel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button type="button" id="clear-dates" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="table-container">
            @if(isset($outputs) && count($outputs) > 0)
            <table class="custom-table">
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
                    <a href="{{ route('outputs.index', ['page' => 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="{{ route('outputs.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Anterior</a>
                @endif
                
                <!-- Mostrar páginas cercanas -->
                @php
                    // Definir cuántas páginas mostrar alrededor de la actual
                    $showPages = 2;
                    $startPage = max(1, $currentPage - $showPages);
                    $endPage = min($lastPage, $currentPage + $showPages);
                @endphp
                
                @for($i = $startPage; $i <= $endPage; $i++)
                    @if($i == $currentPage)
                        <span class="btn btn-primary active btn-custom-size">{{ $i }}</span>
                    @else
                        <a href="{{ route('outputs.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-outline-primary btn-custom-size">{{ $i }}</a>
                    @endif
                @endfor
                
                <form method="GET" action="{{ route('outputs.index') }}" class="d-inline-flex ml-2">
                    <input type="hidden" name="query" value="{{ request('query') }}">
                    <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control page-input" placeholder="Ir a">
                    <button type="submit" class="btn btn-info btn-custom-size ml-1">Ir</button>
                </form>
                
                @if($currentPage < $lastPage)
                    <a href="{{ route('outputs.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Siguiente</a>
                    <a href="{{ route('outputs.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                @endif
                
                <span class="ml-2">Página {{ $currentPage }} de {{ $lastPage }}</span>
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
            
            // Validación del campo de salto de página
            $('.page-input').on('change', function() {
                const maxPage = {{ $lastPage }};
                if (this.value > maxPage) {
                    this.value = maxPage;
                } else if (this.value < 1) {
                    this.value = 1;
                }
            });
        });
    </script>
</body>
</html>
@endsection