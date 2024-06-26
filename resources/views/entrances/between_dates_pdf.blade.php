<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas por Rango de Fechas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Entradas por Rango de Fechas</h1>
    <p>Rango de fechas: {{ request()->input('start_date') }} - {{ request()->input('end_date') }}</p>
    <table>
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
            @foreach($dateRangeData as $entrance)
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
                <td>{{ \Carbon\Carbon::parse($entrance['created_at'])->format('Y-m-d H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>Reporte generado el {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
