<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salida de Producto</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #010101ff;
            margin-bottom: 30px;
            text-align: center;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            border-color: #000000ff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Salida de Producto</h1>

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

        <!-- Formulario de Salida -->
        <div class="card">
            <div class="card-header">
                <h2>Registrar Salida</h2>
            </div>
            <div class="card-body">
                <form id="outputForm" action="{{ route('products.outputs.store') }}" method="POST" class="needs-validation" novalidate>
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

                    <!-- Alertas de cantidad -->
                    <div id="alertaCantidad" class="alert alert-danger d-none" role="alert">
                        La cantidad mínima es 1.
                    </div>

                    <div id="alertaCantidadExcedida" class="alert alert-warning d-none" role="alert">
                        Cantidad excedida. La cantidad disponible es {{ number_format($product['quantity'], 0, '.', ',') }}.
                    </div>

                    <div id="alertaSinStock" class="alert alert-danger d-none" role="alert">
                        No hay stock disponible.
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label for="description">Descripción (Opcional):</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" maxlength="100">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            }).on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $(this).next('.invalid-feedback').hide();
                }
            });

            // Variables
            var maxQuantity = {{ $product['quantity'] }};
            
            // Verificar stock
            if (maxQuantity === 0) {
                $('#alertaSinStock').removeClass('d-none');
                $('#outputForm :input').prop('disabled', true);
                $('#outputForm button[type="submit"]').prop('disabled', true);
            }

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
                $('#alertaCantidadExcedida').addClass('d-none');
                $('#formattedQuantity').removeClass('is-invalid');
                
                if (quantityValue < 1 || isNaN(quantityValue)) {
                    $('#alertaCantidad').removeClass('d-none');
                    $('#formattedQuantity').addClass('is-invalid');
                    return false;
                } else if (quantityValue > maxQuantity) {
                    $('#alertaCantidadExcedida').removeClass('d-none');
                    $('#formattedQuantity').addClass('is-invalid');
                    return false;
                }
                return true;
            }

            // Validación del formulario
            $('#outputForm').on('submit', function(event) {
                event.preventDefault();
                var form = this;
                var projectSelect = $('#project_id');
                var quantityValid = validateQuantity();
                
                // Validar proyecto
                if (projectSelect.val() === '') {
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
                    title: 'Confirmar Salida',
                    html: `<p><strong>Producto:</strong> {{ $product['name'] }}</p>
                          <p><strong>Cantidad a retirar:</strong> ${$('#formattedQuantity').val()}</p>
                          <p><strong>Proyecto:</strong> ${$('#project_id option:selected').text()}</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#030303ff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, confirmar salida',
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
        });
    </script>
</body>
</html>