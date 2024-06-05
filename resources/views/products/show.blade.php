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
                <h1>Entrada</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: ${{ $product['price'] }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <form id="entranceForm" action="{{ route('products.entrances.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="form-group">
                    <label for="project_id">Proyecto:</label>
                    <select id="project_id" name="project_id" class="form-control" required>
                        <option value="">Seleccione un proyecto</option>
                        @if (count($projects) > 0)
                            @foreach ($projects as $project)
                                <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No hay proyectos disponibles</option>
                        @endif
                    </select>
                    <div class="invalid-feedback">Por favor, seleccione un proyecto o marque "Sin proyecto".</div>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="noProjectCheck" name="noProjectCheck">
                    <label class="form-check-label" for="noProjectCheck">Sin proyecto</label>
                </div>

                <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>

                <div class="mb-3">
                    <label for="responsible" class="form-label">Responsable:</label>
                    <input type="text" name="responsible" id="responsible" class="form-control" required maxlength="100">
                    <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                    <div class="invalid-feedback">Por favor, ingrese una cantidad válida.</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción (Opcional):</label>
                    <textarea name="description" id="description" class="form-control" maxlength="100"></textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    {{-- @dd($errors)  --}}
    <!-- Inclusión de JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Inclusión de jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclusión de Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Script para inicializar Select2 y manejar el checkbox -->
    <script>
        $(document).ready(function() {
            $('#project_id').select2({
                placeholder: 'Seleccione un proyecto',
                allowClear: true
            });

            $('#noProjectCheck').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#project_id').val(null).trigger('change');
                    $('#project_id').prop('disabled', true);
                } else {
                    $('#project_id').prop('disabled', false);
                }
            });

            // Validación personalizada del lado del cliente
            $('#entranceForm').on('submit', function(event) {
                var form = this;
                var projectSelect = $('#project_id');
                var noProjectCheck = $('#noProjectCheck');

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

                // Si el checkbox está marcado, elimina el valor del campo project_id
                if (noProjectCheck.is(':checked')) {
                    projectSelect.removeAttr('name');
                }

                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>
