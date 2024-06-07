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
        .form-check-label {
            color: initial;
        }
        .is-invalid .select2-selection {
            border-color: #dc3545;
        }
        .is-valid .select2-selection {
            border-color: #28a745;
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

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el nombre del producto.</div>
            </div>

            <div class="form-group">
                <label for="model">Modelo:</label>
                <input type="text" id="model" name="model" value="{{ old('model') }}" class="form-control @error('model') is-invalid @enderror">
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el modelo del producto o marque "Sin modelo".</div>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noModelCheck" name="noModelCheck">
                <label for="noModelCheck">Sin modelo</label>
            </div>

            <div class="form-group">
                <label for="measurement_unit">Unidad de medida:</label>
                <input type="text" id="measurement_unit" name="measurement_unit" value="{{ old('measurement_unit') }}" class="form-control @error('measurement_unit') is-invalid @enderror">
                @error('measurement_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la unidad de medida o marque "Sin unidad de medida".</div>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noMeasurementUnitCheck" name="noMeasurementUnitCheck">
                <label for="noMeasurementUnitCheck">Sin unidad de medida</label>
            </div>

            <div class="form-group">
                <label for="brand">Marca:</label>
                <input type="text" id="brand" name="brand" value="{{ old('brand') }}" required class="form-control @error('brand') is-invalid @enderror">
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la marca del producto.</div>
            </div>

            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="text" id="quantity" name="quantity" value="{{ old('quantity') }}" required class="form-control @error('quantity') is-invalid @enderror">
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la cantidad del producto. El valor mínimo es 1.</div>
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la descripción del producto.</div>
            </div>
       
            <div class="form-group">
                <label for="price">Precio:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="text" id="price" name="price" value="{{ old('price') }}" required class="form-control" placeholder="0.00" data-type="currency">
                </div>
                <div class="invalid-feedback">Por favor, ingrese el precio del producto. El valor mínimo es 1.</div>
            </div>

            <div class="form-group">
                <label for="profile_image">Imagen:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" onchange="previewImage(event)">
                @error('profile_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, seleccione una imagen del producto.</div>
            </div>

            <div class="image-preview-container">
                <img id="imagePreview" src="#" alt="Vista previa de la imagen">
            </div>

            <div class="form-group">
                <label for="serie">Serie:</label>
                <input type="text" id="serie" name="serie" value="{{ old('serie') }}" class="form-control @error('serie') is-invalid @enderror">
                @error('serie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la serie del producto o marque "Sin serie".</div>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noSerieCheck" name="noSerieCheck">
                <label for="noSerieCheck">Sin serie</label>
            </div>

            <div class="form-group">
                <label for="observations">Observaciones:</label>
                <input type="text" id="observations" name="observations" value="{{ old('observations') }}" class="form-control @error('observations') is-invalid @enderror">
                @error('observations')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese observaciones del producto o marque "Sin observaciones".</div>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noObservationsCheck" name="noObservationsCheck">
                <label for="noObservationsCheck">Sin observaciones</label>
            </div>

            <div class="form-group">
                <label for="location">Ubicación:</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" required class="form-control @error('location') is-invalid @enderror">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la ubicación del producto.</div>
            </div>

            <div class="form-group">
                <label for="supplier_id">Proveedor:</label>
                <select id="supplier_id" name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                    <option value="">Seleccione un proveedor</option>
                    @if (count($suppliers) > 0)
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier['id'] }}" {{ old('supplier_id') == $supplier['id'] ? 'selected' : '' }}>{{ $supplier['company'] }}</option>
                        @endforeach
                    @else
                        <option value="" disabled>No hay proveedores disponibles</option>
                    @endif
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, seleccione un proveedor o marque "Sin proveedor".</div>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="noSupplierCheck" name="noSupplierCheck">
                <label for="noSupplierCheck">Sin proveedor</label>
            </div>

            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">Seleccione una categoría</option>
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>{{ $category['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, seleccione una categoría.</div>
            </div>

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
                        return 'No hay resultados';
                    }
                }
            }).on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(this).next('.invalid-feedback').hide();
                }
            });

            $('#supplier_id').select2({
                placeholder: 'Seleccione un proveedor',
                language: {
                    noResults: function() {
                        return 'No hay resultados';
                    }
                }
            }).on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(this).next('.invalid-feedback').hide();
                }
            });

            toggleInputDisable('noSupplierCheck', 'supplier_id');
            toggleInputDisable('noMeasurementUnitCheck', 'measurement_unit');
            toggleInputDisable('noModelCheck', 'model');
            toggleInputDisable('noSerieCheck', 'serie');
            toggleInputDisable('noObservationsCheck', 'observations');
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
                    if ($(input).hasClass('select2-hidden-accessible')) {
                        $(input).val(null).trigger('change');
                    } else {
                        input.value = '';
                    }
                    input.disabled = true;
                    input.removeAttribute('required');
                    input.classList.remove('is-invalid');
                } else {
                    input.disabled = false;
                    input.setAttribute('required', 'required');
                }
            });
        }

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
                    input.disabled = true;
                    input.removeAttribute('required');
                } else {
                    input.disabled = false;
                    input.setAttribute('required', 'required');
                }
            }

            var requiredInputs = ['name', 'quantity', 'price', 'brand', 'location'];
            for (var i = 0; i < requiredInputs.length; i++) {
                var input = document.getElementById(requiredInputs[i]);
                if (input.value === '' || parseFloat(input.value.replace(/,/g, '')) <= 0) {
                    input.classList.add('is-invalid');
                    if (input.id === 'quantity' && parseFloat(input.value.replace(/,/g, '')) <= 0) {
                        input.nextElementSibling.textContent = 'La cantidad mínima es 1.';
                    }
                    if (input.id === 'price' && parseFloat(input.value.replace(/,/g, '')) <= 0) {
                        input.nextElementSibling.textContent = 'El precio mínimo es 1.';
                    }
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            }

            var categoryInput = document.getElementById('category_id');
            if (categoryInput.value === '') {
                categoryInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
            } else {
                categoryInput.classList.remove('is-invalid');
                categoryInput.classList.add('is-valid');
            }

            var supplierInput = document.getElementById('supplier_id');
            if (supplierInput.value === '' && !document.getElementById('noSupplierCheck').checked) {
                supplierInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
            } else {
                supplierInput.classList.remove('is-invalid');
                supplierInput.classList.add('is-valid');
            }

            // Remove commas from price and quantity before submitting the form
            var priceInput = document.getElementById('price');
            priceInput.value = priceInput.value.replace(/,/g, '');

            var quantityInput = document.getElementById('quantity');
            quantityInput.value = quantityInput.value.replace(/,/g, '');

            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.classList.add('was-validated');
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function () {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.nextElementSibling.style.display = 'none';
                }
            });
        });

        // Function to format number as currency with commas
        function formatNumberWithCommas(number) {
            return number.replace(/\D/g, "")
                         .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Event listener to format price and quantity inputs with commas
        document.querySelectorAll('#price, #quantity').forEach(input => {
            input.addEventListener('input', function (e) {
                var value = e.target.value.replace(/,/g, '');
                if (!isNaN(value) && value !== '') {
                    e.target.value = formatNumberWithCommas(value);
                } else {
                    e.target.value = '';
                }
            });
        });
    </script>
</body>
</html>
