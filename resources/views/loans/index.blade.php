@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos personalizados */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center; /* Asegura que el modal esté centrado horizontalmente */
            min-height: calc(100% - 1rem);
        }
        .modal-content {
            margin: auto;
        }
        .swal2-popup {
            display: flex;
            align-items: center;
            justify-content: center; /* Asegura que las alertas estén centradas horizontalmente */
            min-height: calc(80% - 1rem);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Listado de Préstamos</h1>
        <form method="GET" action="{{ route('loans.index') }}">
            <div class="input-group mb-3">
                <input type="text" name="query" class="form-control" placeholder="Buscar préstamo..." value="{{ request('query') }}">
                <button class="btn" type="submit" style="background-color: #333; color: #fff;">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
        <div class="table-responsive">
            @if(isset($loans) && count($loans) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Observaciones</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr>
                        <td>{{ $loan['product']['name'] }}</td>
                        <td>{{ $loan['responsible'] }}</td>
                        <td>{{ number_format($loan['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                        <td>{{ $loan['observations'] ?? 'N/A' }}</td> 
                        <td>
                            @if($loan['status'] == 0)
                                {{ $loan['updated_at'] ? \Carbon\Carbon::parse($loan['updated_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}
                            @else
                                {{ $loan['created_at'] ? \Carbon\Carbon::parse($loan['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}
                            @endif
                        </td>
                        <td>
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
                                    <button type="button" class="btn btn-primary btn-sm return-product-btn" data-loan-id="{{ $loan['id'] }}">
                                        Regresar Producto
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="{{ route('loans.index', ['page' => $currentPage - 1, 'query' => request('query')]) }}" class="btn btn-primary">Anterior</a>
                @endif
                @for($i = 1; $i <= $lastPage; $i++)
                    <a href="{{ route('loans.index', ['page' => $i, 'query' => request('query')]) }}" class="btn btn-secondary {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                @if($currentPage < $lastPage)
                    <a href="{{ route('loans.index', ['page' => $currentPage + 1, 'query' => request('query')]) }}" class="btn btn-primary">Siguiente</a>
                @endif
                <a href="{{ route('loans.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            @else
            <p>No se encontraron préstamos.</p>
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
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="returnProductForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
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
    <script>
        $(document).ready(function() {
            var currentLoanId = null;

            $('#noObservations').change(function() {
                if ($(this).is(':checked')) {
                    $('#observations').prop('disabled', true);
                } else {
                    $('#observations').prop('disabled', false);
                }
            });

            $('.return-product-btn').click(function() {
                currentLoanId = $(this).data('loan-id');
                $('#loanId').val(currentLoanId); // Asigna el valor del ID del préstamo al campo oculto
                $('#returnProductModal').modal('show');
            });

            $('#confirmReturnProduct').click(function() {
                var observations = $('#noObservations').is(':checked') ? '' : $('#observations').val();
                var loanId = $('#loanId').val();

                if ($('#noObservations').is(':checked') || observations.trim() !== '') {
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
                                    observations: observations // Asegúrate de que las observaciones se envían
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
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe seleccionar "No enviar observaciones" o escribir alguna observación.',
                        icon: 'error',
                        background: '#fff',
                        customClass: {
                            popup: 'swal2-popup'
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
@endsection