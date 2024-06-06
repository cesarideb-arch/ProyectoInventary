<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <!-- Inclusión de Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclusión de Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header">
                <h1>Salida</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: ${{ number_format($product['price'], 2, '.', ',') }}</p>
                <p class="fw-bold">Cantidad de producto: {{ number_format($product['quantity'], 0, '.', ',') }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <form id="outputForm" action="{{ route('products.outputs.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="form-group">
                    <label for="project_id">Proyecto:</label>
                    <select id="project_id" name="project_id" class="form-control" required {{ old('noProjectCheck') ? 'disabled' : '' }}>
                        <option value="">Seleccione un proyecto</option>
                        @if (count($projects) > 0)
                            @foreach ($projects as $project)
                                <option value="{{ $project['id'] }}" {{ old('project_id') == $project['id'] ? 'selected' : '' }}>{{ $project['name'] }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No hay proyectos disponibles</option>
                        @endif
                    </select>
                    <div class="invalid-feedback">Por favor, seleccione un proyecto o marque "Sin proyecto".</div>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="noProjectCheck" name="noProjectCheck" {{ old('noProjectCheck') ? 'checked' : '' }}>
                    <label class="form-check-label" for="noProjectCheck">Sin proyecto</label>
                </div>

                <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>

                <div class="mb-3">
                    <label for="responsible" class="form-label">Responsable:</label>
                    <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') }}">
                    <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="text" name="quantity" id="quantity" class="form-control quantity-input @error('quantity') is-invalid @enderror" required value="{{ old('quantity') }}">
                    <div class="invalid-feedback">Por favor, ingrese la cantidad.</div>
                    <div class="invalid-feedback quantity-error" style="display:none;">La cantidad ingresada excede la cantidad disponible. Cantidad disponible: {{ number_format($product['quantity'], 0, '.', ',') }}.</div>
                    <div class="invalid-feedback no-stock-error" style="display:none;">No hay existencia.</div>
                </div>
  
    <!-- Alerta -->
    <div id="alertaCantidad" class="alert alert-danger d-none" role="alert">
        La cantidad mínima es 1.
    </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción (Opcional):</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" maxlength="100">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>


    <!-- Inclusión de JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Inclusión de jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclusión de Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            var maxQuantity = {{ $product['quantity'] }};
            
            // Deshabilitar el botón de enviar si no hay existencia
            if (maxQuantity === 0) {
                $('#outputForm').find(':input').prop('disabled', true);
                $('.no-stock-error').show();
            }

            $('#project_id').select2({
                placeholder: 'Seleccione un proyecto',
                allowClear: true
            });

            // Verifica si el checkbox está marcado y deshabilita el select si es necesario
            if ($('#noProjectCheck').is(':checked')) {
                $('#project_id').prop('disabled', true);
            }

            $('#noProjectCheck').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#project_id').prop('disabled', true).removeClass('is-invalid');
                } else {
                    $('#project_id').prop('disabled', false);
                }
            });

            $('#project_id').on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid');
                }
            });

            // Validación personalizada del lado del cliente
            $('#outputForm').on('submit', function(event) {
                var form = this;
                var projectSelect = $('#project_id');
                var noProjectCheck = $('#noProjectCheck');
                var quantityInput = $('#quantity');
                var quantityValue = parseFloat(quantityInput.val().replace(/,/g, ''));

                if (projectSelect.val() === '' && !noProjectCheck.is(':checked')) {
                    projectSelect.addClass('is-invalid');
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    projectSelect.removeClass('is-invalid').addClass('is-valid');
                }

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                if (noProjectCheck.is(':checked')) {
                    projectSelect.prop('disabled', true);
                }

                if (quantityValue > maxQuantity) {
                    quantityInput.addClass('is-invalid');
                    $('.quantity-error').show();
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    quantityInput.removeClass('is-invalid').addClass('is-valid');
                    $('.quantity-error').hide();
                }

                if (quantityValue <= 0) {
                    quantityInput.addClass('is-invalid');
                    $('.quantity-zero-error').show();
                    event.preventDefault();
                    event.stopPropagation();
                    $('#alertaCantidad').removeClass('d-none'); // Mostrar la alerta
                } else {
                    $('.quantity-zero-error').hide();
                    $('#alertaCantidad').addClass('d-none'); // Ocultar la alerta
                }

                form.classList.add('was-validated');

                // Eliminar comas antes de enviar el formulario
                quantityInput.val(quantityInput.val().replace(/,/g, ''));
            });

            // Separación correcta de la cantidad
            $('#quantity').on('input', function(e) {
                var value = e.target.value.replace(/,/g, ''); // Elimina las comas existentes
                if (value) {
                    value = parseFloat(value.replace(/[^0-9.]/g, '')).toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2
                    });
                    e.target.value = value;
                }
            });

            // Restaurar la cantidad formateada si existe un valor anterior
            var oldQuantity = '{{ old('quantity') }}';
            if (oldQuantity) {
                var formattedOldQuantity = parseFloat(oldQuantity.replace(/[^0-9.]/g, '')).toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
                $('#quantity').val(formattedOldQuantity);
            }
        });
    </script>
</body>
</html>
