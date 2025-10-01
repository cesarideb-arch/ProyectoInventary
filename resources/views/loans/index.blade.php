@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        body {
            overflow-x: hidden;
            background-color: #f5f7f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        h1 {
            color: #000000ff;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #000000ff;
            color: white;
        }
        
        .btn-danger {
            background: #e53e3e;
            color: white;
        }
        
        .btn-success {
            background: #38a169;
            color: white;
        }
        
        .btn-info {
            background: #000000ff;
            color: white;
        }
        
        .btn-outline-primary {
            border: 1px solid #000000ff;
            color: #000000ff;
            background: transparent;
        }
        
        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }
        
        .btn-custom-size {
            padding: 8px 16px;
        }

        .search-container {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .input-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-radius: 6px;
        }
        
        .form-control {
            border: 1px solid #cbd5e0;
            padding: 10px 15px;
            height: auto;
            font-size: 14px;
        }
        
        .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background: #000000ff;
            color: white;
        }

        /* ESTILOS PARA LA TABLA (COMO LA IMAGEN) CON CABECERA FIJA */
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
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
            background-color: #000000ff; /* Fondo oscuro como en la imagen */
            position: sticky; /* Hacemos el header sticky */
            top: 0; /* Lo fijamos en la parte superior del contenedor */
            z-index: 10; /* Aseguramos que esté por encima del contenido */
        }
        
        .custom-table th {
            padding: 12px 15px;
            text-align: center;
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
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            text-align: center;
        }
        
        .custom-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .custom-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .custom-table tbody tr:hover {
            background-color: #ebf8ff;
        }
        
        .table-action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            transition: all 0.2s;
        }
        
        .table-btn-edit {
            background: #000000ff;
            color: white;
        }
        
        .table-btn-delete {
            background: #e53e3e;
            color: white;
        }
        
        .table-btn-return {
            background: #38a169;
            color: white;
        }
        
        .table-btn-edit:hover, .table-btn-delete:hover, .table-btn-return:hover {
            opacity: 0.85;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .pagination-info {
            font-size: 14px;
            color: #000000ff;
        }

        .pagination {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .pagination span {
            margin: 0 5px;
        }

        .pagination a {
            margin: 0 3px;
        }
        
        .pagination .active {
            font-weight: bold;
            background: #000000ff;
            color: white;
        }

        .pagination .page-input {
            width: 50px;
            display: inline-block;
            margin: 0 5px;
            text-align: center;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            padding: 5px;
            font-size: 14px;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .date-filter-container {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .date-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .date-input-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-input-group label {
            margin-bottom: 0;
            font-weight: 500;
            min-width: 90px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-returned {
            background-color: #38a169;
            color: white;
        }

        .status-loaned {
            background-color: #dd6b20;
            color: white;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .export-buttons {
                width: 100%;
                justify-content: space-between;
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .date-filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .date-input-group {
                flex-direction: column;
                align-items: stretch;
            }
            
            .date-input-group label {
                min-width: auto;
            }
            
            /* Ajustes para móviles en la tabla */
            .table-container {
                max-height: 400px; /* Reducimos la altura máxima en móviles */
            }
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
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 10px;
            }
            
            .custom-table td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                border: none;
                border-bottom: 1px solid #eee;
                text-align: right;
            }
            
            .custom-table td:last-child {
                border-bottom: none;
            }
            
            .custom-table td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 10px;
                color: #2d3748;
                min-width: 100px;
                text-align: left;
            }
            
            .action-buttons {
                justify-content: center;
                width: 100%;
            }
            
            .btn-group-horizontal {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Listado de Préstamos</h1>
        
        <div class="d-flex justify-content-between align-items-center page-header mb-3">
            <div class="export-buttons">
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'month_pdf'])) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF del Mes
                </a>
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'finished_pdf'])) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF Finalizados
                </a>
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'started_pdf'])) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> PDF Iniciados
                </a>
            </div>
            
            <div class="pagination-info">
                @if(isset($monthDataNumber['count']))
                    <p>Conteo de préstamos del mes actual: <strong>{{ number_format($monthDataNumber['count'], 0, ',', '.') }}</strong></p>
                @else
                    <p>No hay datos disponibles.</p>
                @endif
            </div>
        </div>
        
        <div class="search-container">
            <form method="GET" action="{{ route('loans.index') }}">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Buscar préstamo..." value="{{ request('query') }}">
                    <div class="input-group-append">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="date-filter-container">
            <form method="GET" action="{{ route('loans.index') }}" class="date-filter-form">
                <div class="date-input-group">
                    <label for="start_date">Fecha Inicio:</label>
                    <input type="text" name="start_date" placeholder="dd/mm/aaaa" class="form-control datepicker" required>
                </div>
                
                <div class="date-input-group">
                    <label for="end_date">Fecha Fin:</label>
                    <input type="text" name="end_date" placeholder="dd/mm/aaaa" class="form-control datepicker" required>
                </div>
                
                <div class="date-input-group">
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
            </form>
        </div>
        
        <div class="table-container">
            @if(isset($loans) && count($loans) > 0)
            <table class="custom-table">
                <thead>
                    <tr> 
                        <th>Proyecto</th>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Ubicación</th>
                        <th>Motivos de Préstamo</th>
                        <th>Fecha</th>
                        <th>Nombre Cuenta</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr>
                        <td data-label="Proyecto">{{ $loan['project']['name'] ?? 'N/A' }}</td>
                        <td data-label="Producto">{{ $loan['product']['name'] }}</td>
                        <td data-label="Responsable">{{ $loan['responsible'] }}</td>
                        <td data-label="Cantidad">{{ number_format($loan['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                        <td data-label="Ubicación">{{ $loan['product']['location'] ?? 'N/A' }}</td>
                        <td data-label="Motivos de Préstamo">{{ $loan['observations'] ?? 'N/A' }}</td>

                        <td data-label="Fecha">
                            @if($loan['status'] == 0)
                                {{ $loan['updated_at'] ? \Carbon\Carbon::parse($loan['updated_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}
                            @else
                                {{ $loan['created_at'] ? \Carbon\Carbon::parse($loan['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}
                            @endif
                        </td>
                        <td data-label="Nombre Cuenta">{{ $loan['user']['name'] ?? 'N/A' }}</td>
                        <td data-label="Estado">
                            @if($loan['status'] == 0)
                                <span class="status-badge status-returned">Producto Regresado</span>
                            @else
                                <span class="status-badge status-loaned">Producto Prestado</span>
                            @endif
                        </td>
                        <td data-label="Acciones">
                            @if($loan['status'] == 1)
                                <div class="action-buttons">
                                    <button type="button" class="table-action-btn table-btn-return return-product-btn" 
                                            data-loan-id="{{ $loan['id'] }}" data-loan-observations="{{ $loan['observations'] }}">
                                        <i class="fas fa-undo"></i> Regresar
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination-container">
                <div class="pagination-info">
                    Página {{ $currentPage }} de {{ $lastPage }}
                </div>
                
                <div class="pagination">
                    @if($currentPage > 1)
                        <a href="{{ route('loans.index', ['page' => 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                        <a href="{{ route('loans.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Anterior</a>
                    @endif
                    
                    <!-- Mostrar páginas cercanas -->
                    @php
                        $showPages = 2;
                        $startPage = max(1, $currentPage - $showPages);
                        $endPage = min($lastPage, $currentPage + $showPages);
                    @endphp
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span class="btn btn-primary active btn-custom-size">{{ $i }}</span>
                        @else
                            <a href="{{ route('loans.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-outline-primary btn-custom-size">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    <!-- Saltar a página específica -->
                    <form method="GET" action="{{ route('loans.index') }}" class="d-inline-flex ml-2">
                        <input type="hidden" name="query" value="{{ request('query') }}">
                        <input type="number" name="page" min="1" max="{{ $lastPage }}" class="form-control page-input" placeholder="Ir a">
                        <button type="submit" class="btn btn-info btn-custom-size ml-1">Ir</button>
                    </form>
                    
                    @if($currentPage < $lastPage)
                        <a href="{{ route('loans.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">Siguiente</a>
                        <a href="{{ route('loans.index', ['page' => $lastPage, 'query' => request('query')]) }}" class="btn btn-primary btn-custom-size">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
            @else
            <p class="text-center py-4">No se encontraron préstamos.</p>
            @endif
        </div>
    </div>

    <!-- Modal para enviar observaciones -->
    <div class="modal fade" id="returnProductModal" tabindex="-1" role="dialog" aria-labelledby="returnProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnProductModalLabel">Devolver Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="returnProductForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="observations">Motivos de Préstamo (Cambiar Opcional)</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3" disabled></textarea>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="noObservations">
                            <label class="form-check-label" for="noObservations">No enviar Motivos de Préstamo</label>
                        </div>
                        <input type="hidden" id="loanId" name="loanId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmReturnProduct">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentLoanId = null;
            var previousObservations = null;

            $('#noObservations').change(function() {
                if($(this).is(':checked')) {
                    $('#observations').prop('disabled', true);
                } else {
                    $('#observations').prop('disabled', false);
                }
            });

            $('.return-product-btn').click(function() {
                currentLoanId = $(this).data('loan-id');
                previousObservations = $(this).data('loan-observations');
                $('#loanId').val(currentLoanId);
                $('#observations').val(previousObservations).prop('disabled', false);
                $('#noObservations').prop('checked', false);
                $('#returnProductModal').modal('show');
            });

            $('#confirmReturnProduct').click(function() {
                var observations = $('#noObservations').is(':checked') ? null : $('#observations').val();
                var loanId = $('#loanId').val();

                if (!$('#noObservations').is(':checked') && !observations) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Debes ingresar observaciones o marcar la opción de no enviar observaciones.',
                        icon: 'error',
                        background: '#f7fafc',
                        confirmButtonColor: '#000000ff'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Estás seguro de que deseas regresar este producto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000ff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, regresar',
                    cancelButtonText: 'Cancelar',
                    background: '#f7fafc'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/loans/' + loanId,
                            type: 'POST',
                            data: {
                                _method: 'PUT',
                                _token: '{{ csrf_token() }}',
                                loan_id: loanId,
                                observations: observations
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: '¡Regresado!',
                                    text: 'El producto ha sido regresado exitosamente.',
                                    icon: 'success',
                                    background: '#f7fafc',
                                    confirmButtonColor: '#000000ff'
                                }).then(() => {
                                    window.location.href = '{{ route('loans.index') }}';
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al regresar el producto.',
                                    icon: 'error',
                                    background: '#f7fafc',
                                    confirmButtonColor: '#000000ff'
                                });
                                console.error('Error al devolver el préstamo:', error);
                            }
                        });
                    }
                });
            });

            // Configuración del datepicker
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

            $('.datepicker').datepicker({
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