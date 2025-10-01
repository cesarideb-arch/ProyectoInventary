<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada de Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
            margin-bottom: 30px;
            text-align: center;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .card-header {
            background-color: #000000ff;
            color: white;
            font-weight: bold;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #000000ff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #000000ff;
            border-color: #000000ff;
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
        .is-invalid .select2-selection {
            border-color: #dc3545;
        }
        .is-valid .select2-selection {
            border-color: #28a745;
        }
        .alert {
            margin-top: 10px;
        }
        .product-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-check-label {
            color: initial;
        }
        .checkbox-group {
            margin-top: -10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Entrada de Producto</h1>

        <!-- Información del Producto -->
        <div class="card">
            <div class="card-header">
                <h2>Información del Producto</h2>
            </div>
            <div class="card-body">
                <div class="product-info">
                    <h3>{{ $product['name'] }}</h3>
                    <p><strong>Descripción:</strong> {{ $product['description'] }}</p>
                    <p><strong>Precio:</strong> {{ $product['price'] != 0 ? '$' . number_format($product['price'], 2, '.', ',') : 'N/A' }}</p>
                    <p><strong>Cantidad disponible:</strong> {{ number_format($product['quantity'], 0, '.', ',') }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario de Entrada -->
        <div class="card">
            <div class="card-header">
                <h2>Registrar Entrada</h2>
            </div>
            <div class="card-body">
                <form id="entranceForm" action="{{ route('products.entrances.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <!-- Proyecto -->
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

                    <div class="form-group form-check checkbox-group">
                        <input type="checkbox" class="form-check-input" id="noProjectCheck" name="noProjectCheck" {{ old('noProjectCheck') ? 'checked' : '' }}>
                        <label class="form-check-label" for="noProjectCheck">Sin proyecto</label>
                    </div>

                    <input type="hidden" name="product_id" value="{{ $product['id'] }}" required>
                    <input type="hidden" name="user_id" value="{{ session('user_id') }}">

                    <!-- Responsable -->
                    <div class="form-group">
                        <label for="responsible">Responsable:</label>
                        @if (session()->has('name'))
                            <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') ?? session('name') }}">
                        @else
                            <input type="text" name="responsible" id="responsible" class="form-control @error('responsible') is-invalid @enderror" required maxlength="100" value="{{ old('responsible') ?? auth()->user()->name }}">
                        @endif
                        @error('responsible')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback">Por favor, ingrese el nombre del responsable.</div>
                    </div>

                    <!-- Cantidad -->
                    <div class="form-group">
                        <label for="quantity">Cantidad:</label>
                        <input type="text" id="formattedQuantity" class="form-control" value="{{ old('quantity') ? number_format(old('quantity'), 0) : '' }}" required>
                        <input type="hidden" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                        <div class="invalid-feedback">Por favor, ingrese la cantidad.</div>
                    </div>

                    <!-- Alerta de cantidad -->
                    <div id="alertaCantidad" class="alert alert-danger d-none" role="alert">
                        La cantidad mínima es 1.
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="description">Descripción (Opcional):</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" maxlength="100">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Folio -->
                    <div class="form-group">
                        <label for="folio">Folio:</label>
                        <input type="text" name="folio" id="folio" class="form-control @error('folio') is-invalid @enderror" required maxlength="100" value="{{ old('folio') }}">
                        @error('folio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback">Por favor, ingrese el folio.</div>
                    </div>

                    <!-- Botones -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('#project_id').select2({
                placeholder: 'Seleccione un proyecto',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No hay resultados";
                    }
                }
            });

            // Funciones de formateo
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

            // Formatear cantidad inicial
            var quantityInput = document.getElementById('quantity');
            var formattedQuantityInput = document.getElementById('formattedQuantity');
            
            if (quantityInput.value) {
                formattedQuantityInput.value = formatNumber(quantityInput.value);
            }

            // Event listener para formateo en tiempo real
            formattedQuantityInput.addEventListener('input', function (e) {
                var formattedValue = formatNumber(e.target.value);
                e.target.value = formattedValue;
                document.getElementById('quantity').value = unformatNumber(formattedValue);
                
                // Validar cantidad
                validateQuantity();
            });

            // Validar cantidad
            function validateQuantity() {
                var quantityValue = parseFloat($('#quantity').val().replace(/,/g, '') || 0);
                
                $('#alertaCantidad').addClass('d-none');
                $('#formattedQuantity').removeClass('is-invalid');
                
                if (quantityValue < 1 || isNaN(quantityValue)) {
                    $('#alertaCantidad').removeClass('d-none');
                    $('#formattedQuantity').addClass('is-invalid');
                    return false;
                }
                return true;
            }

            // Toggle para proyecto
            function toggleProjectInput() {
                var checkbox = document.getElementById('noProjectCheck');
                var projectSelect = document.getElementById('project_id');
                
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        $(projectSelect).val(null).trigger('change');
                        projectSelect.disabled = true;
                        projectSelect.removeAttribute('required');
                        projectSelect.classList.remove('is-invalid');
                    } else {
                        projectSelect.disabled = false;
                        projectSelect.setAttribute('required', 'required');
                    }
                });

                // Estado inicial
                if (checkbox.checked) {
                    projectSelect.disabled = true;
                    projectSelect.removeAttribute('required');
                } else {
                    projectSelect.disabled = false;
                    projectSelect.setAttribute('required', 'required');
                }
            }

            // Inicializar toggle de proyecto
            toggleProjectInput();

            // Validación del formulario
            $('#entranceForm').on('submit', function(event) {
                event.preventDefault();
                var form = this;
                var projectSelect = $('#project_id');
                var noProjectCheck = $('#noProjectCheck');
                var quantityValid = validateQuantity();
                
                // Validar proyecto
                if (projectSelect.val() === '' && !noProjectCheck.is(':checked')) {
                    projectSelect.addClass('is-invalid');
                    event.stopPropagation();
                    return false;
                } else {
                    projectSelect.removeClass('is-invalid').addClass('is-valid');
                }
                
                if (!quantityValid || !form.checkValidity()) {
                    event.stopPropagation();
                    form.classList.add('was-validated');
                    return false;
                }
                
                // Mostrar confirmación
                Swal.fire({
                    title: 'Confirmar Entrada',
                    html: `<p><strong>Producto:</strong> {{ $product['name'] }}</p>
                          <p><strong>Cantidad a ingresar:</strong> ${$('#formattedQuantity').val()}</p>
                          <p><strong>Proyecto:</strong> ${noProjectCheck.is(':checked') ? 'Sin proyecto' : $('#project_id option:selected').text()}</p>
                          <p><strong>Folio:</strong> ${$('#folio').val()}</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000ff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, confirmar entrada',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Remover comas antes de enviar
                        $('#quantity').val($('#quantity').val().replace(/,/g, ''));
                        form.submit();
                    }
                });
                
                form.classList.add('was-validated');
            });

            // Validación en tiempo real para campos de texto
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('input', function () {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.nextElementSibling.style.display = 'none';
                    }
                });
            });

            // Restaurar la cantidad formateada si existe un valor anterior
            var oldQuantity = '{{ old('quantity') }}';
            if (oldQuantity) {
                var formattedOldQuantity = formatNumber(oldQuantity);
                $('#formattedQuantity').val(formattedOldQuantity);
                $('#quantity').val(unformatNumber(formattedOldQuantity));
            }
        });
    </script>
</body>
</html>