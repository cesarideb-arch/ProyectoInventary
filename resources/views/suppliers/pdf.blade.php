<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Lista de Proveedores</h1>
        @php
            $chunks = array_chunk($suppliers, 16);
        @endphp
        @foreach($chunks as $index => $chunk)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Precio</th>
                            <th class="text-center">Empresa</th>
                            <th class="text-center">Teléfono</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chunk as $supplier)
                            <tr>
                                <td class="text-center">${{ number_format($supplier['price'], 2, '.', ',') }}</td>
                                <td class="text-center">{{ $supplier['company'] }}</td>
                                <td class="text-center">{{ $supplier['phone'] }}</td>
                                <td class="text-center">{{ $supplier['email'] ?? 'N/A' }}</td>
                                <td class="text-center">{{ $supplier['address'] ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
