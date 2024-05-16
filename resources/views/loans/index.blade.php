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
                <input type="text" name="query" class="form-control" placeholder="Buscar prestamos..." value="{{ request('query') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
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
                        <td>{{ \Carbon\Carbon::parse($loan['created_at'])->format('Y-m-d H:i:s') }}</td>
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
            @else
            <p>No se encontraron préstamos.</p>
            @endif
        </div>
    </div>

    <!-- Tus scripts aquí -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.return-product-btn').click(function() {
                var loanId = $(this).data('loan-id');
                if (confirm('¿Estás seguro de que deseas regresar este producto?')) {
                    $.ajax({
                        url: '/loans/' + loanId, // Ruta a tu controlador para actualizar el préstamo
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            loan_id: loanId
                        },
                        success: function(response) {
                            // Puedes agregar aquí alguna notificación de éxito si lo deseas
                            // Por ejemplo: alert('Préstamo devuelto exitosamente');
                            // Pero en este caso, simplemente redireccionamos a la página de listado de préstamos
                            window.location.href = '{{ route('loans.index') }}';
                        },
                        error: function(xhr, status, error) {
                            // Manejo de errores
                            console.error('Error al devolver el préstamo:', error);
                        }
                    });
                }
            });
        });
    </script>
    {{-- @dd($errors) --}}
</body>
</html>
@endsection
