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
    <style>
        /* Tus estilos aquí */
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
                        <td>{{ $loan['quantity'] }}</td>
                        <td>{{ $loan['created_at'] ? \Carbon\Carbon::parse($loan['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d h:i:s A') : 'N/A' }}</td>
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
                </a>            </div>
            </div>
            @else
            <p>No se encontraron préstamos.</p>
            @endif
        </div>
    </div>

    <!-- Tus scripts aquí -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.return-product-btn').click(function() {
                var loanId = $(this).data('loan-id');
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Estás seguro de que deseas regresar este producto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, regresar',
                    cancelButtonText: 'Cancelar',
                    background: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/loans/' + loanId, // Ruta a tu controlador para actualizar el préstamo
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                loan_id: loanId
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: '¡Regresado!',
                                    text: 'El producto ha sido regresado exitosamente.',
                                    icon: 'success',
                                    background: '#fff'
                                }).then(() => {
                                    window.location.href = '{{ route('loans.index') }}';
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un problema al regresar el producto.',
                                    icon: 'error',
                                    background: '#fff'
                                });
                                console.error('Error al devolver el préstamo:', error);
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
@endsection
