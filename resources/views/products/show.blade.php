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
                <h1>Entrada</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: ${{ $product['price'] }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <h1>Proyectos</h1>
            <form id="entranceForm" action="{{ route('products.entrances.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="form-group">
                    <label for="project_id">Proyecto:</label>
                    <select id="project_id" name="project_id" class="form-control">
                        <option value="">Seleccione un proyecto</option>
                        @if (count($projects) > 0)
                            @foreach ($projects as $project)
                                <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No hay proyectos disponibles</option>
                        @endif
                    </select>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="noProjectCheck" name="noProjectCheck">
                    <label class="form-check-label" for="noProjectCheck">Sin proyecto</label>
                </div>

                <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>

                <div class="mb-3">
                    <label for="responsible" class="form-label">Responsable:</label>
                    <input type="text" name="responsible" id="responsible" class="form-control" required maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción (Opcional):</label>
                    <textarea name="description" id="description" class="form-control" maxlength="100"></textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Regresar</a>
                </div>
            </form>
        </div>
    </div>
    {{-- @dd($errors)  --}}
    <!-- Opcional: Inclusión de JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para manejar el checkbox y enviar project_id como null -->
    <script>
        document.getElementById('noProjectCheck').addEventListener('change', function() {
            var projectSelect = document.getElementById('project_id');
            if (this.checked) {
                projectSelect.value = '';
                projectSelect.disabled = true;
            } else {
                projectSelect.disabled = false;
            }
        });

        // Validación personalizada del lado del cliente
        document.getElementById('entranceForm').addEventListener('submit', function(event) {
            var form = event.target;
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            // Si el checkbox está marcado, elimina el valor del campo project_id
            if (document.getElementById('noProjectCheck').checked) {
                document.getElementById('project_id').removeAttribute('name');
            }

            form.classList.add('was-validated');
        });
    </script>
</body>
</html>
