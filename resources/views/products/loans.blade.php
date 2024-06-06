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
                <p class="fw-bold">Precio: ${{ number_format($product['price'], 2, '.', ',') }}</p>
                <p class="fw-bold">Cantidad de producto: {{ number_format($product['quantity'], 0, '.', ',') }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <form id="loanForm" action="{{ route('products.loans.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>

                <div class="mb-3">
                    <label for="responsible" class="form-label">Responsable:</label>
                    <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') }}">
                    <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" required value="{{ old('quantity') }}" min="1">
                    <div class="invalid-feedback">Por favor, ingrese la cantidad.</div>
                    <div class="invalid-feedback quantity-error" style="display:none;">La cantidad ingresada excede la cantidad disponible. Cantidad disponible: {{ number_format($product['quantity'], 0, '.', ',') }}.</div>
                    <div class="invalid-feedback no-stock-error" style="display:none;">No hay existencia.</div>

                </div>
                <!-- Alerta -->
    <div id="alertaCantidad" class="alert alert-danger d-none" role="alert">
        La cantidad mínima es 1.
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
            
            // Deshabilitar el botón de enviar si no hay existencia
            if (maxQuantity === 0) {
                $('#loanForm').find(':input').prop('disabled', true);
                $('.no-stock-error').show();
            }

            // Validación personalizada del lado del cliente
            $('#loanForm').on('submit', function(event) {
                var form = this;
                var quantityInput = $('#quantity');
                var quantityValue = parseFloat(quantityInput.val());

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

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            });

            // Restaurar la cantidad formateada si existe un valor anterior
            var oldQuantity = '{{ old('quantity') }}';
            if (oldQuantity) {
                $('#quantity').val(oldQuantity);
            }
        });
    </script>
</body>
</html>
