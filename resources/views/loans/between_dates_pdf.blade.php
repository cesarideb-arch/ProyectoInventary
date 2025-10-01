<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos por Rango de Fechas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-size: 13px;
            font-family: Arial, sans-serif;
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
            background-color: #f2f2f2;
            font-weight: bold;
            white-space: nowrap;
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
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>Préstamos por Rango de Fechas</h1>
    <br>
    <p>Rango de fechas: {{ request()->input('start_date') }} - {{ request()->input('end_date') }}</p>
    <div class="table-responsive">
        <table class="table table-striped text-center">
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
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dateRangeData as $loan)
                <tr>
                    <td>{{ $loan['project']['name'] ?? 'N/A' }}</td>
                    <td>{{ $loan['product']['name'] }}</td>
                    <td>{{ $loan['responsible'] }}</td>
                    <td>{{ number_format($loan['quantity'] ?? 'N/A', 0, '.', ',') }}</td>
                    <td>{{ $loan['product']['location'] ?? 'N/A' }}</td>
                    <td>{{ $loan['observations'] ?? 'N/A' }}</td>
                    <td>
                        @if($loan['status'] == 0)
                            {{ $loan['updated_at'] ? \Carbon\Carbon::parse($loan['updated_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}
                        @else
                            {{ $loan['created_at'] ? \Carbon\Carbon::parse($loan['created_at'])->setTimezone('America/Mexico_City')->format('Y-m-d H:i:s') : 'N/A' }}
                        @endif
                    </td>
                    <td>{{  $loan['user']['name'] ?? 'N/A' }}</td>
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
</body>
</html>
