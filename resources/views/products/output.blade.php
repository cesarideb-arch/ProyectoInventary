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
                <h1>Salidas</h1>
            </div>
            <div class="card-body">
                <h2>{{ $product['name'] }}</h2>
                <p>{{ $product['description'] }}</p>
                <p class="fw-bold">Precio: ${{ $product['price'] }}</p>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <h1>Proyectos</h1>
            <form action="{{ route('products.outputs.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="project_id" class="form-label">Proyecto:</label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        @foreach ($projects as $project)
                        <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                        @endforeach
                    </select>
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
                    <label for="date" class="form-label">Fecha:</label>
                    <input type="datetime-local" name="date" id="date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Opcional: Inclusión de JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para establecer automáticamente la fecha y hora actual en el campo de fecha -->
    <script>
        window.onload = function() {
            var today = new Date();
            var dd = today.getDate().toString().padStart(2, '0');
            var mm = (today.getMonth() + 1).toString().padStart(2, '0'); // Enero es 0!
            var yyyy = today.getFullYear().toString();
            var hh = today.getHours().toString().padStart(2, '0');
            var min = today.getMinutes().toString().padStart(2, '0');
            var formattedDate = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
            document.getElementById('date').value = formattedDate;
        }
    </script>
</body>
</html>