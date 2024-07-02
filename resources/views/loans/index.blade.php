@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos</title>
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
        /* Estilos personalizados */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100% - 1rem);
        }
        .pagination span {
            margin: 0 10px;
        }

        .pagination a {
            margin: 0 5px; /* Ajusta este valor según sea necesario */
        }

        .modal-content {
            margin: auto;
        }
        .swal2-popup {
            width: 90% !important;
            max-width: 600px;
            padding: 40px;
            text-align: center;
        }
        .swal2-icon, .swal2-title, .swal2-content {
            margin: 20px auto;
        }
        .btn-custom-size {
            margin: 0 10px 10px 0;
            text-align: left;
        }

        /* Centrar textos */
        p, th, td {
            text-align: center;
        }

        @media (max-width: 576px) {
            .swal2-popup {
                width: 100% !important;
            }
            .btn-custom-size {
                margin-bottom: 15px;
                width: calc(100% - 8px);
            }
            .btn-group-left,
            .btn-group-right {
                display: flex;
                flex-direction: column;
                flex: 1;
            }
            .d-flex.flex-wrap {
                flex-direction: row;
                justify-content: space-between;
            }
            .ml-auto {
                width: 100%;
                text-align: center;
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
        <h1 class="mb-4">Listado de Préstamos</h1>
        <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap">
            <div class="btn-group-left" style="flex-wrap: wrap;">
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'pdf'])) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 5px;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'month_pdf'])) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 5px;">
                    <i class="fas fa-file-pdf"></i> PDF del Mes
                </a>
            </div>
            <div class="btn-group-right" style="flex-wrap: wrap;">
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'finished_pdf'])) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 5px;">
                    <i class="fas fa-file-pdf"></i> PDF Finalizados
                </a>
                <a href="{{ route('loans.index', array_merge(request()->query(), ['download' => 'started_pdf'])) }}" class="btn btn-danger btn-custom-size" style="background-color: red; border-radius: 5px;">
                    <i class="fas fa-file-pdf"></i> PDF Iniciados
                </a>
            </div>
            <div class="ml-auto">
                @if(isset($monthDataNumber['count']))
                    <p class="mb-10">Conteo de préstamos del mes actual: {{ number_format($monthDataNumber['count'], 0, ',', '.') }}</p>
                @else
                    <p class="mb-10">No hay datos disponibles.</p>
                @endif
            </div>
        </div>
        <form method="GET" action="{{ route('loans.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar préstamo..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <form method="GET" action="{{ route('loans.index') }}" class="form-inline mb-3">
            <div class="input-group">
                <input type="text" name="start_date" placeholder="Fecha Inicio" class="form-control datepicker mr-2" required>
                <input type="text" name="end_date" placeholder="Fecha Fin" class="form-control datepicker mr-2" required>
                <div class="btn-group" role="group">
                    <button type="submit" name="download" value="between_dates_pdf" class="btn btn-danger btn-custom-size" style="background-color: red;">
                        <i class="fas fa-file-pdf"></i> PDF por Fechas
                    </button>
                    <button type="submit" name="download" value="between_dates_excel" class="btn btn-success btn-custom-size" style="text-align: center;">
                        <i class="fas fa-file-excel"></i> Excel por Fechas
                    </button>
                    <button type="button" id="clear-dates" class="btn btn-secondary btn-custom-size">
                        <i class="fas fa-eraser"></i> Limpiar Fechas
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($loans) && count($loans) > 0)
            <table class="table table-striped">
                <thead>
                    <tr> 
                        <th>Proyecto</th>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Ubicación</th>
                        <th>Observaciones</th>
                        <th>Fecha</th>
                        <th>Nombre Cuenta</th>
                        <th>Estado</th>
                        <th></th>
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
                        <td data-label="Observaciones">{{ $loan['observations'] ?? 'N/A' }}</td>
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
                                Producto Regresado
                            @else
                                Producto Prestado
                            @endif
                        </td>
                        <td>
                            @if($loan['status'] == 1)
                                <div class="btn-group btn-group-horizontal" role="group">
                                    <!-- Botón de Regresar Producto -->
                                    <button type="button" class="btn btn-primary btn-sm return-product-btn" data-loan-id="{{ $loan['id'] }}" data-loan-observations="{{ $loan['observations'] }}">
                                        Regresar Producto
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($currentPage > 1)
            <a href="{{ route('loans.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
        @endif
    
        <span>Página {{ $currentPage }} de {{ $lastPage }}</span>
    
        @if($currentPage < $lastPage)
            <a href="{{ route('loans.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
        @endif
    
        @if($currentPage > 1)
            <a href="{{ route('loans.index') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Inicio
            </a>
        @endif
            @else
            <p style="text-align: left;">No se encontraron préstamos.</p>
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
                            <label for="observations">Observaciones (Cambiar Opcional)</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3" disabled></textarea>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="noObservations">
                            <label class="form-check-label" for="noObservations">No enviar observaciones</label>
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
                        background: '#fff',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Estás seguro de que deseas regresar este producto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, regresar',
                    cancelButtonText: 'Cancelar',
                    background: '#fff',
                    customClass: {
                        popup: 'swal2-popup'
                    }
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
                                    background: '#fff',
                                    customClass: {
                                        popup: 'swal2-popup'
                                    }
                                }).then(() => {
                                    window.location.href = '{{ route('loans.index') }}';
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al regresar el producto.',
                                    icon: 'error',
                                    background: '#fff',
                                    customClass: {
                                        popup: 'swal2-popup'
                                    }
                                });
                                console.error('Error al devolver el préstamo:', error);
                            }
                        });
                    }
                });
            });
        });

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

            $('.datepicker').datepicker({
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
