<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Crear Producto</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control">
            </div>

            <div class="form-group">
                <label for="model">Modelo:</label>
                <input type="text" id="model" name="model" value="{{ old('model') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="unit_measure">Unidad de medida:</label>
                <input type="text" id="unit_measure" name="unit_measure" value="{{ old('unit_measure') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="brand">Marca:</label>
                <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" required class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" required class="form-control">
            </div>
            
        <div class="form-group">
                <label for="profile_image">Imagen:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control">
            </div> 

            <div class="form-group">
                <label for="provider">Proveedor:</label>
                <input type="text" id="provider" name="provider" value="{{ old('provider') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="serie">Serie:</label>
                <input type="text" id="serie" name="serie" value="{{ old('serie') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="observations">Observaciones:</label>
                <input type="text" id="observations" name="observations" value="{{ old('observations') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="location">Ubicación:</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="category">Categoría:</label>
                <input type="text" id="category" name="category" value="{{ old('category') }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Crear Producto</button>
        </form>
        @dd($errors->all())
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
