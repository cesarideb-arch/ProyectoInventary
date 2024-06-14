<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <!-- Inclusión de Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header">
                <h1>Préstamo</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: {{ $product['price'] != 0 ? '$' . number_format($product['price'], 2, '.', ',') : 'N/A' }}</p>
                <p class="fw-bold">Cantidad de producto: {{ number_format($product['quantity'], 0, '.', ',') }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <form id="entranceForm" action="{{ route('products.loans.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>

                <div class="mb-3">
                    <label for="responsible" class="form-label">Responsable:</label>
                    <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') }}">
                    <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="text" name="quantity" id="quantity" class="form-control quantity-input @error('quantity') is-invalid @enderror" required value="{{ old('quantity') }}">
                    <div class="invalid-feedback">Por favor, ingrese la cantidad</div>
                </div>

                <div id="alertaSinStock" class="alert alert-danger d-none" role="alert">
                    No hay stock disponible.
                </div>

                <div id="alertaCantidad" class="alert alert-danger d-none" role="alert">
                    La cantidad mínima es 1.
                </div>

                <div id="alertaCantidadExcedida" class="alert alert-warning d-none" role="alert">
                    Cantidad excedida. La cantidad disponible es {{ number_format($product['quantity'], 0, '.', ',') }}.
                </div>

                <div class="mb-3">
                    <label for="observations" class="form-label">Observaciones:</label>
                    <textarea name="observations" id="observations" class="form-control @error('observations') is-invalid @enderror" maxlength="255">{{ old('observations') }}</textarea>
                    <div class="invalid-feedback">Por favor, ingrese observaciones válidas.</div>
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

    <script>
        $(document).ready(function() {
            var maxQuantity = {{ $product['quantity'] }};
            
            // Verifica si hay stock disponible y deshabilita los campos si no lo hay
            if (maxQuantity === 0) {
                $('#alertaSinStock').removeClass('d-none');
                $('#entranceForm :input').prop('disabled', true);
            }

            // Validación personalizada del lado del cliente
            $('#entranceForm').on('submit', function(event) {
                var form = this;
                var quantityInput = $('#quantity');
                var quantityValue = parseFloat(quantityInput.val().replace(/,/g, ''));

                if (quantityValue < 1) {
                    $('#alertaCantidad').removeClass('d-none');
                    $('#alertaCantidadExcedida').addClass('d-none');
                    quantityInput.addClass('is-invalid');
                    event.preventDefault();
                    return false;
                } else if (quantityValue > maxQuantity) {
                    $('#alertaCantidadExcedida').removeClass('d-none');
                    $('#alertaCantidad').addClass('d-none');
                    quantityInput.addClass('is-invalid');
                    event.preventDefault();
                    return false;
                } else {
                    $('#alertaCantidad').addClass('d-none');
                    $('#alertaCantidadExcedida').addClass('d-none');
                    quantityInput.removeClass('is-invalid');
                }

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');

                // Eliminar comas antes de enviar el formulario
                quantityInput.val(quantityInput.val().replace(/,/g, ''));
            });

            // Separación correcta de la cantidad
            var quantityInput = document.getElementById('quantity');
            quantityInput.addEventListener('input', function(e) {
                var value = e.target.value.replace(/,/g, ''); // Elimina las comas existentes
                if (value) {
                    value = parseFloat(value.replace(/[^0-9.]/g, '')).toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2
                    });
                    e.target.value = value;
                }
            });

            // Validación para no permitir ceros y cantidades superiores a las disponibles
            $('#quantity').on('blur', function() {
                var quantityValue = parseFloat($(this).val().replace(/,/g, ''));
                if (quantityValue < 1) {
                    $('#alertaCantidad').removeClass('d-none');
                    $('#alertaCantidadExcedida').addClass('d-none');
                    $(this).addClass('is-invalid');
                } else if (quantityValue > maxQuantity) {
                    $('#alertaCantidadExcedida').removeClass('d-none');
                    $('#alertaCantidad').addClass('d-none');
                    $(this).addClass('is-invalid');
                } else {
                    $('#alertaCantidad').addClass('d-none');
                    $('#alertaCantidadExcedida').addClass('d-none');
                    $(this).removeClass('is-invalid');
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
