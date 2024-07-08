<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos - PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .page-break {
            page-break-before: always;
        }
        .small-text {
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Inventario General</h1>
        @php
            $chunks = array_chunk($products, 16);
        @endphp
        @foreach($chunks as $index => $chunk)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            <div class="table-responsive small-text">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Observaciones</th>
                            <th>Categoría</th>
                            <th>Proveedor</th>
                            <th>Ubicación</th>
                            <th>Cantidad</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chunk as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['description'] ?? 'N/A' }}</td>
                                <td>${{ number_format($product['price'], 2, '.', ',') }}</td>
                                <td>{{ $product['observations'] ?? 'N/A' }}</td>
                                <td>{{ $product['category']['name'] }}</td>
                                <td>{{ $product['supplier']['company'] ?? 'N/A' }}</td>
                                <td>{{ $product['location'] ?? 'N/A' }}</td>
                                <td>{{ number_format($product['quantity'] ?? 0, 0, '.', ',') }}</td>
                                <td>
                                    <img src="{{ config('app.backend_api') }}/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen" width="100" style="border-radius: 10px;">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
