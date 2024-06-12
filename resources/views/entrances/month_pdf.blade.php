<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas del Mes - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Entradas del Mes</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Producto</th>
                        <th>Responsable</th>
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthData as $entrance)
                    <tr>
                        <td>{{ $entrance['project']['name'] ?? 'N/A' }}</td>
                        <td>{{ $entrance['product']['name'] ?? 'N/A' }}</td>
                        <td>{{ $entrance['responsible'] ?? 'N/A' }}</td>
                        <td>{{ number_format($entrance['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                        <td>{{ $entrance['description'] ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($entrance['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
