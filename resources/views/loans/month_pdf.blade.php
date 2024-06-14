<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos del Mes - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Préstamos del Mes</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Observaciones</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthData as $loan)
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
