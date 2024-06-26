<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas del Mes - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-size: 13px;
        }
        .page-break {
            page-break-before: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 5px;
            border: 1px solid #dee2e6;
            word-wrap: break-word;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            white-space: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Entradas del Mes</h1>
        @php
            $chunks = array_chunk($monthData, 16);
        @endphp
        @foreach($chunks as $index => $chunk)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped text-center">
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
                        @foreach($chunk as $entrance)
                            <tr>
                                <td>{{ $entrance['project']['name'] ?? 'N/A' }}</td>
                                <td>{{ $entrance['product']['name'] ?? 'N/A' }}</td>
                                <td>{{ $entrance['responsible'] ?? 'N/A' }}</td>
                                <td>{{ number_format($entrance['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                                <td>{{ number_format($entrance['price'] ?? 'N/A', 2, '.', ',') }}</td>
                                <td>{{ number_format(($entrance['price'] ?? 0) * ($entrance['quantity'] ?? 0), 2, '.', ',') }}</td>
                                <td>{{ $entrance['product']['location'] ?? 'N/A' }}</td>
                                <td>{{ $entrance['description'] ?? 'N/A' }}</td>
                                <td>{{ $entrance['folio'] ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($entrance['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
