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
                <h1>Salida</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: ${{ $product['price'] }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <h1>Proyectos</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="outputForm" action="{{ route('products.outputs.store') }}" method="POST" class="needs-validation" novalidate>
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
                    <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') }}">
                    @error('responsible')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" required value="{{ old('quantity') }}">
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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

    <!-- Opcional: Inclusión de JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Validación personalizada del lado del cliente
        document.getElementById('noProjectCheck').addEventListener('change', function() {
            var projectSelect = document.getElementById('project_id');
            if (this.checked) {
                projectSelect.value = '';
                projectSelect.disabled = true;
                projectSelect.removeAttribute('required');
            } else {
                projectSelect.disabled = false;
                projectSelect.setAttribute('required', 'required');
            }
        });

        document.getElementById('outputForm').addEventListener('submit', function(event) {
            var form = event.target;
            var projectSelect = document.getElementById('project_id');
            var noProjectCheck = document.getElementById('noProjectCheck');

            if (projectSelect.value === '' && !noProjectCheck.checked) {
                projectSelect.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
            } else {
                projectSelect.classList.remove('is-invalid');
                projectSelect.classList.add('is-valid');
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            if (noProjectCheck.checked) {
                projectSelect.removeAttribute('name');
            }

            form.classList.add('was-validated');
        });
    </script>
</body>
</html>
