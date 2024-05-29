<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        }
        #imagePreview {
            width: 100%;
            max-width: 300px;
            height: auto;
            display: none;
            margin-top: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
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
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
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

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noModelCheck" name="noModelCheck">
                <label class="form-check-label" for="noModelCheck">Sin modelo</label>
            </div>

            <div class="form-group">
                <label for="measurement_unit">Unidad de medida:</label>
                <input type="text" id="measurement_unit" name="measurement_unit" value="{{ old('measurement_unit') }}" class="form-control">
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noMeasurementUnitCheck" name="noMeasurementUnitCheck">
                <label class="form-check-label" for="noMeasurementUnitCheck">Sin unidad de medida</label>
            </div>

            <div class="form-group">
                <label for="brand">Marca:</label>
                <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required class="form-control" min="1">
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

          <div class="form-group">
    <label for="price">Precio:</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">$</span>
        </div>
        <input type="text" id="price" name="price" value="{{ old('price') }}" required class="form-control" min="0.01" placeholder="0.00">
    </div>
</div>

<script>
    document.getElementById('price').addEventListener('input', function (e) {
        var value = e.target.value;
        value = value.replace(/,/g, ''); // Eliminar las comas existentes
        if (!isNaN(value) && value !== '') {
            var parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            e.target.value = parts.join('.');
        }
    });
</script>


            <div class="form-group">
                <label for="profile_image">Imagen:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control" onchange="previewImage(event)">
            </div>

            <div class="image-preview-container">
                <img id="imagePreview" src="#" alt="Vista previa de la imagen">
            </div>

            <div class="form-group">
                <label for="serie">Serie:</label>
                <input type="text" id="serie" name="serie" value="{{ old('serie') }}" class="form-control">
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noSerieCheck" name="noSerieCheck">
                <label class="form-check-label" for="noSerieCheck">Sin serie</label>
            </div>

            <div class="form-group">
                <label for="observations">Observaciones:</label>
                <input type="text" id="observations" name="observations" value="{{ old('observations') }}" class="form-control">
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noObservationsCheck" name="noObservationsCheck">
                <label class="form-check-label" for="noObservationsCheck">Sin observaciones</label>
            </div>

            <div class="form-group">
                <label for="location">Ubicación:</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="supplier_id">Proveedor:</label>
                <select id="supplier_id" name="supplier_id" class="form-control">
                    <option value="">Seleccione un proveedor</option>
                    @if (count($suppliers) > 0)
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier['id']}}">{{ $supplier['company']}}</option>
                        @endforeach
                    @else
                        <option value="" disabled>No hay proveedores disponibles</option>
                    @endif
                </select>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noSupplierCheck" name="noSupplierCheck">
                <label class="form-check-label" for="noSupplierCheck">Sin proveedor</label>
            </div>

            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">Seleccione una categoría</option>
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            <option value="{{ $category['id']}}">{{ $category['name']}}</option>
                        @endforeach
                    @else
                        <option value="" disabled>No hay categorías disponibles</option>
                    @endif
                </select>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noCategoryCheck" name="noCategoryCheck">
                <label class="form-check-label" for="noCategoryCheck">Sin categoría</label>
            </div>

            <script>
                var categoryCheckbox = document.getElementById('noCategoryCheck');
                var categorySelect = document.getElementById('category_id');

                categoryCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        categorySelect.value = '';
                        categorySelect.disabled = true;
                        categorySelect.classList.add('is-invalid');
                    } else {
                        categorySelect.disabled = false;
                        categorySelect.classList.remove('is-invalid');
                    }
                });
            </script>

            <button type="submit" class="btn btn-primary">Crear Producto</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category_id').select2({
                placeholder: 'Seleccione una categoría',
                language: {
                    noResults: function() {
                        return 'No hay categorías disponibles con ese nombre';
                    }
                }
            });

            $('#supplier_id').select2({
                placeholder: 'Seleccione un proveedor',
                language: {
                    noResults: function() {
                        return 'No hay proveedores disponibles con ese nombre';
                    }
                }
            });
        });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function toggleInputDisable(checkboxId, inputId) {
            var checkbox = document.getElementById(checkboxId);
            var input = document.getElementById(inputId);
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    input.value = '';
                    input.disabled = true;
                    input.removeAttribute('required');
                } else {
                    input.disabled = false;
                    input.setAttribute('required', 'required');
                }
            });
        }

        toggleInputDisable('noSupplierCheck', 'supplier_id');
        toggleInputDisable('noMeasurementUnitCheck', 'measurement_unit');
        toggleInputDisable('noModelCheck', 'model');
        toggleInputDisable('noSerieCheck', 'serie');
        toggleInputDisable('noObservationsCheck', 'observations');

        document.querySelector('form').addEventListener('submit', function(event) {
            var inputs = ['supplier_id', 'measurement_unit', 'model', 'serie', 'observations'];
            var checkboxes = ['noSupplierCheck', 'noMeasurementUnitCheck', 'noModelCheck', 'noSerieCheck', 'noObservationsCheck'];

            for (var i = 0; i < inputs.length; i++) {
                var input = document.getElementById(inputs[i]);
                var checkbox = document.getElementById(checkboxes[i]);

                if (input.value === '' && !checkbox.checked) {
                    input.classList.add('is-invalid');
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }

                if (checkbox.checked) {
                    input.removeAttribute('name');
                }
            }

            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.classList.add('was-validated');
        });
    </script>
</body>
</html>
