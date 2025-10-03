<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .employee-form-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .form-title {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: bold;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            text-align: center;
        }
        .form-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .form-section-title {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .form-table {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }
        .form-row {
            display: table-row;
        }
        .form-cell {
            display: table-cell;
            padding: 10px 15px;
            vertical-align: top;
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .btn-agregar {
            background-color: #3498db;
            border-color: #3498db;
            font-weight: 500;
            padding: 8px 25px;
            border-radius: 5px;
        }
        .btn-agregar:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-agregar-salida {
            background-color: #28a745;
            border-color: #28a745;
            font-weight: 500;
            padding: 8px 25px;
            border-radius: 5px;
            margin-left: 10px;
        }
        .btn-agregar-salida:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .checkbox-group .form-check-input {
            margin-top: 0;
        }
        .checkbox-group label {
            margin-left: 8px;
            margin-bottom: 0;
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
        @media (max-width: 768px) {
            .form-table, .form-row, .form-cell {
                display: block;
            }
            .form-cell {
                padding: 8px 0;
            }
            .btn-group-mobile {
                display: flex;
                flex-direction: column;
            }
            .btn-group-mobile .btn {
                margin-bottom: 10px;
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container employee-form-container">
        <h1 class="form-title">Crear Producto</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" novalidate id="productForm">
            @csrf
            <input type="hidden" name="redirect_to_output" id="redirect_to_output" value="0">
            
            <div class="form-section">
                <h5 class="form-section-title">Información Básica del Producto</h5>
                <div class="form-table">
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Nombre:</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Marca:</label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand') }}" required class="form-control @error('brand') is-invalid @enderror">
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Modelo:</label>
                            <input type="text" id="model" name="model" value="{{ old('model') }}" class="form-control @error('model') is-invalid @enderror">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noModelCheck" name="noModelCheck">
                                <label for="noModelCheck" class="form-check-label">Sin modelo</label>
                            </div>
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Unidad de medida:</label>
                            <input type="text" id="measurement_unit" name="measurement_unit" value="{{ old('measurement_unit') }}" class="form-control @error('measurement_unit') is-invalid @enderror">
                            @error('measurement_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noMeasurementUnitCheck" name="noMeasurementUnitCheck">
                                <label for="noMeasurementUnitCheck" class="form-check-label">Sin unidad de medida</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Número de serie:</label>
                            <input type="text" id="serie" name="serie" value="{{ old('serie') }}" class="form-control @error('serie') is-invalid @enderror">
                            @error('serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noSerieCheck" name="noSerieCheck">
                                <label for="noSerieCheck" class="form-check-label">Sin número de serie</label>
                            </div>
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Ubicación:</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" required class="form-control @error('location') is-invalid @enderror">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h5 class="form-section-title">Detalles del Producto</h5>
                <div class="form-table">
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Cantidad:</label>
                            <input type="text" id="formattedQuantity" class="form-control" value="{{ old('quantity') ? number_format(old('quantity')) : '' }}" required>
                            <input type="hidden" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Precio:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="text" id="formattedPrice" class="form-control" value="{{ old('price') ? number_format(old('price'), 2) : '0.00' }}" required>
                                <input type="hidden" id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Categoría:</label>
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
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Proveedor:</label>
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
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noSupplierCheck" name="noSupplierCheck">
                                <label for="noSupplierCheck" class="form-check-label">Sin proveedor</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Descripción:</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Observaciones:</label>
                            <input type="text" id="observations" name="observations" value="{{ old('observations') }}" class="form-control @error('observations') is-invalid @enderror">
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noObservationsCheck" name="noObservationsCheck">
                                <label for="noObservationsCheck" class="form-check-label">Sin observaciones</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Imagen:</label>
                            <input type="file" id="profile_image" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" onchange="previewImage(event)">
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="image-preview-container">
                                <img id="imagePreview" src="#" alt="Vista previa de la imagen">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
          <div class="d-flex justify-content-end mt-4 btn-group-mobile">
    <button type="button" onclick="window.history.back()" class="btn btn-secondary mr-2">Cancelar</button>
    <button type="submit" class="btn btn-agregar" id="btnCrearProducto">Crear Producto</button>
</div>
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
                }
            });

            toggleInputDisable('noSupplierCheck', 'supplier_id');
            toggleInputDisable('noMeasurementUnitCheck', 'measurement_unit');
            toggleInputDisable('noModelCheck', 'model');
            toggleInputDisable('noSerieCheck', 'serie');
            toggleInputDisable('noObservationsCheck', 'observations');

            // Evento para el botón "Crear y Agregar Salida"
            $('#btnCrearYAgregarSalida').on('click', function() {
                $('#redirect_to_output').val('1');
                $('#productForm').submit();
            });

            // Evento para el botón normal "Crear Producto"
            $('#btnCrearProducto').on('click', function() {
                $('#redirect_to_output').val('0');
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
            
            // Estado inicial
            if (checkbox.checked) {
                if ($(input).hasClass('select2-hidden-accessible')) {
                    $(input).val(null).trigger('change');
                } else {
                    input.value = '';
                }
                input.disabled = true;
                input.removeAttribute('required');
            }
            
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

        function formatNumber(value) {
            value = value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                return parseInt(value).toLocaleString();
            }
            return value;
        }

        function unformatNumber(value) {
            return value.replace(/,/g, '');
        }

        function formatPrice(value) {
            value = value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                var parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return parts.join('.');
            }
            return value;
        }

        function unformatPrice(value) {
            return value.replace(/,/g, '');
        }

        document.getElementById('formattedQuantity').addEventListener('input', function (e) {
            var formattedValue = formatNumber(e.target.value);
            e.target.value = formattedValue;
            document.getElementById('quantity').value = unformatNumber(formattedValue);
        });

        document.getElementById('formattedPrice').addEventListener('input', function (e) {
            var formattedValue = formatPrice(e.target.value);
            e.target.value = formattedValue;
            document.getElementById('price').value = unformatPrice(formattedValue);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var quantityInput = document.getElementById('quantity');
            var formattedQuantityInput = document.getElementById('formattedQuantity');
            var initialValue = quantityInput.value;
            formattedQuantityInput.value = formatNumber(initialValue);

            var priceInput = document.getElementById('price');
            var formattedPriceInput = document.getElementById('formattedPrice');
            var initialPriceValue = priceInput.value;
            formattedPriceInput.value = formatPrice(initialPriceValue);
        });

        document.getElementById('productForm').addEventListener('submit', function(event) {
            var inputs = ['supplier_id', 'measurement_unit', 'model', 'serie', 'observations'];
            var checkboxes = ['noSupplierCheck', 'noMeasurementUnitCheck', 'noModelCheck', 'noSerieCheck', 'noObservationsCheck'];
            var allValid = true;

            for (var i = 0; i < inputs.length; i++) {
                var input = document.getElementById(inputs[i]);
                var checkbox = document.getElementById(checkboxes[i]);

                if (input.value === '' && !checkbox.checked) {
                    input.classList.add('is-invalid');
                    allValid = false;
                } else {
                    input.classList.remove('is-invalid');
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
                if (input.value === '' || parseFloat(input.value.replace(/,/g, '')) < 0) {
                    input.classList.add('is-invalid');
                    if (input.id === 'quantity' && parseFloat(input.value.replace(/,/g, '')) <= 0) {
                        input.nextElementSibling.textContent = 'La cantidad mínima es 1.';
                    }
                    if (input.id === 'price' && parseFloat(input.value.replace(/,/g, '')) < 0) {
                        input.nextElementSibling.textContent = 'El precio no puede ser negativo.';
                    }
                    allValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            }

            var categoryInput = document.getElementById('category_id');
            if (categoryInput.value === '') {
                categoryInput.classList.add('is-invalid');
                allValid = false;
            } else {
                categoryInput.classList.remove('is-invalid');
            }

            // Remove commas from price and quantity before submitting the form
            var priceInput = document.getElementById('price');
            priceInput.value = priceInput.value.replace(/,/g, '');

            var quantityInput = document.getElementById('quantity');
            quantityInput.value = quantityInput.value.replace(/,/g, '');

            if (!allValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.classList.add('was-validated');
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function () {
                if (this.value !== '') {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });
    </script>
    
</body>
</html>