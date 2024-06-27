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
    <!-- Inclusión de SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header">
                <h1>Entrada</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: {{ $product['price'] != 0 ? '$' . number_format($product['price'], 2, '.', ',') : 'N/A' }}</p>
                <p class="fw-bold">Cantidad de producto: {{ number_format($product['quantity'], 0, '.', ',') }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <form id="entranceForm" action="{{ route('products.entrances.store') }}" method="POST" class="needs-validation" novalidate>
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
                    <div class="invalid-feedback">Por favor, seleccione un proyecto.</div>
                </div>

                <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>

                <!-- Campo oculto para enviar el ID del usuario -->
                <input type="hidden" name="user_id" value="{{ session('user_id') }}">

                @if (session()->has('name'))
                    <div class="mb-3">
                        <label for="responsible" class="form-label">Responsable:</label>
                        <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') ?? session('name') }}">
                        <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                    </div>
                @else
                    <div class="mb-3">
                        <label for="responsible" class="form-label">Responsable:</label>
                        <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') ?? auth()->user()->name }}">
                        <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="text" name="quantity" id="quantity" class="form-control quantity-input @error('quantity') is-invalid @enderror" required value="{{ old('quantity') }}">
                    <div class="invalid-feedback">Por favor, ingrese la cantidad</div>
                </div>

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
                    <label for="folio" class="form-label">Folio:</label>
                    <input type="text" name="folio" id="folio" class="form-control @error('folio') is-invalid @enderror" required maxlength="100" value="{{ old('folio') }}">
                    <div class="invalid-feedback">Por favor, ingrese el folio.</div>
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
    <!-- Inclusión de SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#project_id').select2({
                placeholder: 'Seleccione un proyecto',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No hay resultados";
                    }
                }
            });

            // Verifica si el checkbox está marcado y deshabilita el select si es necesario
            if ($('#noProjectCheck').is(':checked')) {
                $('#project_id').prop('disabled', true);
            }

            $('#noProjectCheck').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#project_id').val(null).trigger('change');
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
            $('#entranceForm').on('submit', function(event) {
                event.preventDefault(); // Prevenir envío del formulario inicial
                var form = this;
                var projectSelect = $('#project_id');
                var noProjectCheck = $('#noProjectCheck');
                var quantityInput = $('#quantity');
                var quantityValue = parseFloat(quantityInput.val().replace(/,/g, ''));

                if (quantityValue < 1 || isNaN(quantityValue)) {
                    $('#alertaCantidad').removeClass('d-none');
                    quantityInput.addClass('is-invalid');
                    return false;
                } else {
                    $('#alertaCantidad').addClass('d-none');
                    quantityInput.removeClass('is-invalid');
                }

                if (projectSelect.val() === '' && !noProjectCheck.is(':checked')) {
                    projectSelect.addClass('is-invalid');
                    event.stopPropagation();
                } else {
                    projectSelect.removeClass('is-invalid').addClass('is-valid');
                }

                if (!form.checkValidity()) {
                    event.stopPropagation();
                } else {
                    // Mostrar confirmación antes de enviar con swal2
                    Swal.fire({
                        title: 'Confirmar envío',
                        html: `<p> <strong>Producto: </strong> {{ $product['name'] }}</p><p> <strong>Cantidad a enviar: </strong> ${quantityInput.val()}</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, enviar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Eliminar comas antes de enviar el formulario
                            quantityInput.val(quantityInput.val().replace(/,/g, ''));
                            form.submit();
                        }
                    });
                }
                form.classList.add('was-validated');
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

            // Validación para no permitir ceros
            $('#quantity').on('blur', function() {
                var quantityValue = parseFloat($(this).val().replace(/,/g, ''));
                if (quantityValue < 1) {
                    $('#alertaCantidad').removeClass('d-none');
                    $(this).addClass('is-invalid');
                } else {
                    $('#alertaCantidad').addClass('d-none');
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
