<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Salidas - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Listado de Salidas</h1>
        @php
            $chunks = array_chunk($outputs, 16);
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
                            <th>Descripción</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chunk as $output)
                            <tr>
                                <td>{{ $output['project']['name'] ?? 'N/A' }}</td>
                                <td>{{ $output['product']['name'] ?? '' }}</td>
                                <td>{{ $output['responsible'] }}</td>
                                <td>{{ number_format($output['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                                <td>{{ $output['product']['location'] ?? 'N/A' }}</td>
                                <td>{{ $output['description'] ?? 'N/A'}}</td>
                                <td>{{ \Carbon\Carbon::parse($output['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
