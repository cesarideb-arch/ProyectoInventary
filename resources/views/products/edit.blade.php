<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
        }
        .image-preview-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            flex-direction: column;
        }
        #imagePreview, #currentImage {
            width: 100%;
            max-width: 300px;
            height: auto;
            display: none;
            margin-top: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        #currentImage {
            display: block;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
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
                <input type="file" id="profile_image" name="profile_image" class="form-control" onchange="previewImage(event)">
            </div>

            <div class="image-preview-container">
                {{-- Imagen actual --}}
                <img id="currentImage" src="http://localhost:8000/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen">
                {{-- Vista previa de la nueva imagen --}}
                <img id="imagePreview" src="#" alt="Vista previa de la nueva imagen">
            </div>

            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select class="form-control" id="category_id" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}" {{ $category['id'] == $product['category_id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="supplier_id">Proveedor:</label>
                <select class="form-control" id="supplier_id" name="supplier_id">
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier['id'] }}" {{ $supplier['id'] == $product['supplier_id'] ? 'selected' : '' }}>
                            {{ $supplier['company'] }}
                        </option>
                    @endforeach
                </select>
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
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Regresar</a>
        </form>
    </div>
     {{-- @dd($errors)  --}}

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';

                // Ocultar la imagen actual cuando se carga una nueva imagen
                document.getElementById('currentImage').style.display = 'none';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Mostrar la vista previa de la imagen actual
        document.addEventListener('DOMContentLoaded', function() {
            var currentImage = document.getElementById('currentImage');
            if (currentImage.src) {
                currentImage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
