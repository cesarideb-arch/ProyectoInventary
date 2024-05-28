<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
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
            flex-direction: column;
        }
        #imagePreview, #currentImage {
            width: 100%;
            max-width: 300px;
            height: auto;
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
                <label for="model">Modelo:</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ $product['model'] }}" {{ $product['model'] == null ? 'disabled' : '' }}>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noModelCheck" name="noModelCheck" {{ $product['model'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noModelCheck">Sin modelo</label>
            </div>

            <div class="form-group">
                <label for="measurement_unit">Unidad de medida:</label>
                <input type="text" class="form-control" id="measurement_unit" name="measurement_unit" value="{{ $product['measurement_unit'] }}" {{ $product['measurement_unit'] == null ? 'disabled' : '' }}>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noMeasurementUnitCheck" name="noMeasurementUnitCheck" {{ $product['measurement_unit'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noMeasurementUnitCheck">Sin unidad de medida</label>
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
                <label for="description">Descripción:</label>
                <textarea class="form-control" id="description" name="description">{{ $product['description'] }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product['price'] }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="profile_image">Imagen:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control" onchange="previewImage(event)">
            </div>

            <div class="image-preview-container">
                {{-- Imagen actual --}}
                <img id="currentImage" src="{{ config('app.backend_api') }}/{{ isset($product['profile_image']) ? $product['profile_image'] : 'ruta_por_defecto_de_la_imagen.jpg' }}" alt="Sin Imagen">

                {{-- Vista previa de la nueva imagen --}}
                <img id="imagePreview" src="#" alt="Vista previa de la nueva imagen" style="display: none;">
            </div>

            <div class="form-group">
                <label for="serie">Serie:</label>
                <input type="text" class="form-control" id="serie" name="serie" value="{{ $product['serie'] }}" {{ $product['serie'] == null ? 'disabled' : '' }}>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noSerieCheck" name="noSerieCheck" {{ $product['serie'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noSerieCheck">Sin serie</label>
            </div>

            <div class="form-group">
                <label for="observations">Observaciones:</label>
                <input type="text" class="form-control" id="observations" name="observations" value="{{ $product['observations'] }}" {{ $product['observations'] == null ? 'disabled' : '' }}>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noObservationsCheck" name="noObservationsCheck" {{ $product['observations'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noObservationsCheck">Sin observaciones</label>
            </div>

            <div class="form-group">
                <label for="location">Ubicación:</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ $product['location'] }}">
            </div>

            <div class="form-group">
                <label for="supplier_id">Proveedor:</label>
                <select class="form-control" id="supplier_id" name="supplier_id" {{ $product['supplier_id'] == null ? 'disabled' : '' }}>
                    <option value="">Seleccione un proveedor</option>
                    @if(count($suppliers) > 0)
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier['id'] }}" {{ $supplier['id'] == $product['supplier_id'] ? 'selected' : '' }}>
                                {{ $supplier['company'] }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No hay proveedores disponibles</option>
                    @endif
                </select>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noSupplierCheck" name="noSupplierCheck" {{ $product['supplier_id'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noSupplierCheck">Sin proveedor</label>
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

            {{-- Botón para enviar el formulario --}}
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn

.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category_id').select2({
                placeholder: 'Seleccione una categoría',
                allowClear: true
            });

            $('#supplier_id').select2({
                placeholder: 'Seleccione un proveedor',
                allowClear: true
            });

            var noSupplierCheck = document.getElementById('noSupplierCheck');
            var supplierSelect = document.getElementById('supplier_id');
            toggleInput(noSupplierCheck, supplierSelect);

            var noMeasurementUnitCheck = document.getElementById('noMeasurementUnitCheck');
            var measurementUnitInput = document.getElementById('measurement_unit');
            toggleInput(noMeasurementUnitCheck, measurementUnitInput);

            var noModelCheck = document.getElementById('noModelCheck');
            var modelInput = document.getElementById('model');
            toggleInput(noModelCheck, modelInput);

            var noSerieCheck = document.getElementById('noSerieCheck');
            var serieInput = document.getElementById('serie');
            toggleInput(noSerieCheck, serieInput);

            var noObservationsCheck = document.getElementById('noObservationsCheck');
            var observationsInput = document.getElementById('observations');
            toggleInput(noObservationsCheck, observationsInput);
        });

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

        function toggleInput(checkbox, input) {
            if (checkbox.checked) {
                input.value = '';
                input.disabled = true;
                input.removeAttribute('required');
            } else {
                input.disabled = false;
                input.setAttribute('required', 'required');
            }
        }

        document.getElementById('noSupplierCheck').addEventListener('change', function() {
            var supplierSelect = document.getElementById('supplier_id');
            toggleInput(this, supplierSelect);
        });

        document.getElementById('noMeasurementUnitCheck').addEventListener('change', function() {
            var measurementUnitInput = document.getElementById('measurement_unit');
            toggleInput(this, measurementUnitInput);
        });

        document.getElementById('noModelCheck').addEventListener('change', function() {
            var modelInput = document.getElementById('model');
            toggleInput(this, modelInput);
        });

        document.getElementById('noSerieCheck').addEventListener('change', function() {
            var serieInput = document.getElementById('serie');
            toggleInput(this, serieInput);
        });

        document.getElementById('noObservationsCheck').addEventListener('change', function() {
            var observationsInput = document.getElementById('observations');
            toggleInput(this, observationsInput);
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            var supplierSelect = document.getElementById('supplier_id');
            var noSupplierCheck = document.getElementById('noSupplierCheck');
            var measurementUnitInput = document.getElementById('measurement_unit');
            var noMeasurementUnitCheck = document.getElementById('noMeasurementUnitCheck');
            var modelInput = document.getElementById('model');
            var noModelCheck = document.getElementById('noModelCheck');
            var serieInput = document.getElementById('serie');
            var noSerieCheck = document.getElementById('noSerieCheck');
            var observationsInput = document.getElementById('observations');
            var noObservationsCheck = document.getElementById('noObservationsCheck');

            validateInput(supplierSelect, noSupplierCheck);
            validateInput(measurementUnitInput, noMeasurementUnitCheck);
            validateInput(modelInput, noModelCheck);
            validateInput(serieInput, noSerieCheck);
            validateInput(observationsInput, noObservationsCheck);

            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            if (noSupplierCheck.checked) {
                supplierSelect.removeAttribute('name');
            }

            if (noMeasurementUnitCheck.checked) {
                measurementUnitInput.removeAttribute('name');
            }

            if (noModelCheck.checked) {
                modelInput.removeAttribute('name');
            }

            if (noSerieCheck.checked) {
                serieInput.removeAttribute('name');
            }

            if (noObservationsCheck.checked) {
                observationsInput.removeAttribute('name');
            }

            this.classList.add('was-validated');
        });

        function validateInput(input, checkbox) {
            if (input.value === '' && !checkbox.checked) {
                input.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        }
    </script>
</body>
</html>