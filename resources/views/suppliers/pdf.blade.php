<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Lista de Proveedores</h1>
    <table>
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Precio</th>
                <th>Empresa</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier['article'] }}</td>
                    <td>${{ number_format($supplier['price'], 2, '.', ',') }}</td>
                    <td>{{ $supplier['company'] }}</td>
                    <td>{{ $supplier['phone'] }}</td>
                    <td>{{ $supplier['email'] ?? 'N/A' }}</td>
                    <td>{{ $supplier['address'] ?? 'N/A'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
