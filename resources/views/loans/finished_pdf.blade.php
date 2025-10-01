<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Finalizados - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Préstamos Finalizados</h1>
        @php
            $chunks = array_chunk($finishedData, 16);
        @endphp
        @foreach($chunks as $index => $chunk)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chunk as $loan)
                            <tr>
                                <td>{{ $loan['project']['name'] }}</td>
                                <td>{{ $loan['product']['name'] }}</td>
                                <td>{{ $loan['responsible'] }}</td>
                                <td>{{ number_format($loan['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                                <td>{{ $loan['product']['location'] ?? 'N/A' }}</td>
                                <td>{{ $loan['observations'] ?? 'N/A' }}</td>
                                <td>{{ $loan['updated_at'] ? \Carbon\Carbon::parse($loan['updated_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                <td>{{  $loan['user']['name'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
