<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Inventario General</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Ubicación</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['description'] ?? 'N/A' }}</td>
                            <td>${{ number_format($product['price'], 2, '.', ',') }}</td>
                            <td>{{ $product['category']['name'] }}</td>
                            <td>{{ $product['supplier']['company'] ?? 'N/A' }}</td>
                            <td>{{ $product['location'] ?? 'N/A' }}</td>
                            <td>
                                <img src="{{ config('app.backend_api') }}/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen" width="100" style="border-radius: 10px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
