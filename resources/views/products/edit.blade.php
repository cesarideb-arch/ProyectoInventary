@extends('layouts.app')

@section('title', 'Crear producto')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
<div class="container employee-form-container">
   
</head>
<body>
    <div class="container">
        <h1>Editar Producto</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product['id']) }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ $product['name'] }}" required class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el nombre del producto.</div>
            </div>

            <!-- Modelo -->
            <div class="form-group">
                <label for="model">Modelo:</label>
                <input type="text" id="model" name="model" value="{{ $product['model'] }}" class="form-control @error('model') is-invalid @enderror">
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el modelo del producto o marque "Sin modelo".</div>
            </div>
            <div class="form-group form-check checkbox-group">
                <input type="checkbox" class="form-check-input" id="noModelCheck" name="noModelCheck" {{ $product['model'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noModelCheck">Sin modelo</label>
            </div>

            <!-- Unidad de Medida -->
            <div class="form-group">
                <label for="measurement_unit">Unidad de medida:</label>
                <input type="text" id="measurement_unit" name="measurement_unit" value="{{ $product['measurement_unit'] }}" class="form-control @error('measurement_unit') is-invalid @enderror">
                @error('measurement_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la unidad de medida o marque "Sin unidad de medida".</div>
            </div>
            <div class="form-group form-check checkbox-group">
                <input type="checkbox" class="form-check-input" id="noMeasurementUnitCheck" name="noMeasurementUnitCheck" {{ $product['measurement_unit'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noMeasurementUnitCheck">Sin unidad de medida</label>
            </div>

            <!-- Marca -->
            <div class="form-group">
                <label for="brand">Marca:</label>
                <input type="text" id="brand" name="brand" value="{{ $product['brand'] }}" required class="form-control @error('brand') is-invalid @enderror">
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la marca del producto.</div>
            </div>

            <!-- Cantidad -->
            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="text" id="formattedQuantity" class="form-control" value="{{ number_format($product['quantity']) }}" required>
                <input type="hidden" id="quantity" name="quantity" value="{{ $product['quantity'] }}" required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la cantidad del producto.</div>
            </div>

            <!-- Descripción -->
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ $product['description'] }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la descripción del producto.</div>
            </div>

            <!-- Precio -->
            <div class="form-group">
                <label for="price">Precio:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="text" id="formattedPrice" class="form-control" value="{{ number_format($product['price'], 2) }}" required>
                    <input type="hidden" id="price" name="price" value="{{ $product['price'] }}" required>
                </div>
                <div class="invalid-feedback">Por favor, ingrese el precio del producto.</div>
            </div>

            <!-- Imagen -->
            <div class="form-group">
                <label for="profile_image">Imagen:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" onchange="previewImage(event)">
                @error('profile_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, seleccione una imagen del producto.</div>
            </div>

            <div class="image-preview-container">
                <img id="currentImage" src="{{ config('app.backend_api') }}/{{ $product['profile_image'] }}" alt="Imagen actual">
                <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="display: none;">
            </div>

            <!-- Número de Serie -->
            <div class="form-group">
                <label for="serie">Número de serie:</label>
                <input type="text" id="serie" name="serie" value="{{ $product['serie'] }}" class="form-control @error('serie') is-invalid @enderror">
                @error('serie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese el número de serie del producto o marque "Sin número de serie".</div>
            </div>
            <div class="form-group form-check checkbox-group">
                <input type="checkbox" class="form-check-input" id="noSerieCheck" name="noSerieCheck" {{ $product['serie'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noSerieCheck">Sin número de serie</label>
            </div>

            <!-- Observaciones -->
            <div class="form-group">
                <label for="observations">Observaciones:</label>
                <input type="text" id="observations" name="observations" value="{{ $product['observations'] }}" class="form-control @error('observations') is-invalid @enderror">
                @error('observations')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese observaciones del producto o marque "Sin observaciones".</div>
            </div>
            <div class="form-group form-check checkbox-group">
                <input type="checkbox" class="form-check-input" id="noObservationsCheck" name="noObservationsCheck" {{ $product['observations'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noObservationsCheck">Sin observaciones</label>
            </div>

            <!-- Ubicación -->
            <div class="form-group">
                <label for="location">Ubicación:</label>
                <input type="text" id="location" name="location" value="{{ $product['location'] }}" required class="form-control @error('location') is-invalid @enderror">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, ingrese la ubicación del producto.</div>
            </div>

            <!-- Proveedor -->
            <div class="form-group">
                <label for="supplier_id">Proveedor:</label>
                <select id="supplier_id" name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
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
                @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, seleccione un proveedor o marque "Sin proveedor".</div>
            </div>
            <div class="form-group form-check checkbox-group">
                <input type="checkbox" class="form-check-input" id="noSupplierCheck" name="noSupplierCheck" {{ $product['supplier_id'] == null ? 'checked' : '' }}>
                <label class="form-check-label" for="noSupplierCheck">Sin proveedor</label>
            </div>

            <!-- Categoría -->
            <div class="form-group">
                <label for="category_id">Categoría:</label>
                <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">Seleccione una categoría</option>
                    @if(count($categories) > 0)
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}" {{ $category['id'] == $product['category_id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Por favor, seleccione una categoría.</div>
            </div>

            <!-- Botones -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Formatos de números
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

        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            // Formatear valores iniciales
            var quantityInput = document.getElementById('quantity');
            var formattedQuantityInput = document.getElementById('formattedQuantity');
            formattedQuantityInput.value = formatNumber(quantityInput.value);

            var priceInput = document.getElementById('price');
            var formattedPriceInput = document.getElementById('formattedPrice');
            formattedPriceInput.value = formatPrice(priceInput.value);

            // Event listeners para formateo en tiempo real
            formattedQuantityInput.addEventListener('input', function (e) {
                var formattedValue = formatNumber(e.target.value);
                e.target.value = formattedValue;
                document.getElementById('quantity').value = unformatNumber(formattedValue);
            });

            formattedPriceInput.addEventListener('input', function (e) {
                var formattedValue = formatPrice(e.target.value);
                e.target.value = formattedValue;
                document.getElementById('price').value = unformatPrice(formattedValue);
            });

            // Inicializar Select2
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

            // Inicializar checkboxes
            toggleInputDisable('noSupplierCheck', 'supplier_id');
            toggleInputDisable('noMeasurementUnitCheck', 'measurement_unit');
            toggleInputDisable('noModelCheck', 'model');
            toggleInputDisable('noSerieCheck', 'serie');
            toggleInputDisable('noObservationsCheck', 'observations');
        });

        // Función para previsualizar imagen
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
                document.getElementById('currentImage').style.display = 'none';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Función para toggle de inputs
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

            // Estado inicial
            if (checkbox.checked) {
                input.disabled = true;
                input.removeAttribute('required');
            } else {
                input.disabled = false;
                input.setAttribute('required', 'required');
            }
        }

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(event) {
            var inputs = ['supplier_id', 'measurement_unit', 'model', 'serie', 'observations'];
            var checkboxes = ['noSupplierCheck', 'noMeasurementUnitCheck', 'noModelCheck', 'noSerieCheck', 'noObservationsCheck'];

            // Validar campos opcionales
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

            // Validar campos requeridos
            var requiredInputs = ['name', 'quantity', 'price', 'brand', 'location'];
            for (var i = 0; i < requiredInputs.length; i++) {
                var input = document.getElementById(requiredInputs[i]);
                if (input.value === '' || (input.id !== 'quantity' && parseFloat(input.value.replace(/,/g, '')) < 0)) {
                    input.classList.add('is-invalid');
                    if (input.id === 'price' && parseFloat(input.value.replace(/,/g, '')) < 0) {
                        input.nextElementSibling.textContent = 'El precio no puede ser negativo.';
                    }
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                }
            }

            // Validar categoría
            var categoryInput = document.getElementById('category_id');
            if (categoryInput.value === '') {
                categoryInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
            } else {
                categoryInput.classList.remove('is-invalid');
                categoryInput.classList.add('is-valid');
            }

            // Validar proveedor
            var supplierInput = document.getElementById('supplier_id');
            if (supplierInput.value === '' && !document.getElementById('noSupplierCheck').checked) {
                supplierInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
            } else {
                supplierInput.classList.remove('is-invalid');
                supplierInput.classList.add('is-valid');
            }

            // Remover comas antes de enviar
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

        // Validación en tiempo real
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function () {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.nextElementSibling.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
@endsection