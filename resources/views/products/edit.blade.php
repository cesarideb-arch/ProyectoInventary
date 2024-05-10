<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <br>
        <h1>Editar Producto</h1>

        {{-- Formulario para editar el producto --}}
        <form method="POST" action="{{ route('products.update', $product['id']) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Campos del formulario --}}
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product['name'] }}" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea class="form-control" id="description" name="description" required>{{ $product['description'] }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product['price'] }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="model">Modelo:</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ $product['model'] }}">
            </div>

                        <div class="form-group">
                            <label for="measurement_unit">Unidad de medida:</label>
                            <input type="text" class="form-control" id="measurement_unit" name="measurement_unit" value="{{ $product['measurement_unit'] }}">
                        </div>


            <div class="form-group">
                <label for="brand">Marca:</label>
                <input type="text" class="form-control" id="brand" name="brand" value="{{ $product['brand'] }}">
            </div>

            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product['quantity'] }}" required>
            </div>

                 
        <div class="form-group">
            <label for="profile_image">Imagen:</label>
            <input type="file" id="profile_image" name="profile_image" class="form-control">
        </div> 


        
            <div class="form-group">
                <label for="serie">Serie:</label>
                <input type="text" class="form-control" id="serie" name="serie" value="{{ $product['serie'] }}">
            </div>

            <div class="form-group">
                <label for="observations">Observaciones:</label>
                <input type="text" class="form-control" id="observations" name="observations" value="{{ $product['observations'] }}">
            </div>

            <div class="form-group">
                <label for="location">Ubicación:</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ $product['location'] }}">
            </div>

            

            {{-- Botón para enviar el formulario --}}
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Volver atrás</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
