@extends('layouts.app')

@section('title', 'Crear producto')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
<div class="container employee-form-container">
    </style>
</head>
<body>
    <div class="container employee-form-container">
        <h1 class="form-title">Editar Proyecto</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('projects.update', $project['id']) }}" novalidate>
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h5 class="form-section-title">Información del Proyecto</h5>
                <div class="form-table">
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Nombre:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $project['name'] }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Descripción:</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ $project['description'] }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Nombre de la Empresa:</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ $project['company_name'] }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">RFC:</label>
                            <input type="text" class="form-control @error('rfc') is-invalid @enderror" id="rfc" name="rfc" value="{{ $project['rfc'] }}" maxlength="13">
                            @error('rfc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noRfcCheck" name="noRfcCheck" {{ empty($project['rfc']) ? 'checked' : '' }}>
                                <label for="noRfcCheck" class="form-check-label">Sin RFC</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Dirección:</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ $project['address'] }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Ubicación:</label>
                            <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion" name="ubicacion" value="{{ $project['ubicacion'] ?? '' }}" maxlength="100">
                            @error('ubicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Teléfono:</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ $project['phone_number'] }}" maxlength="10" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $project['email'] }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Nombre del Cliente:</label>
                            <input type="text" class="form-control @error('client_name') is-invalid @enderror" id="client_name" name="client_name" value="{{ $project['client_name'] }}" required>
                            @error('client_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <!-- Espacio reservado para mantener el layout -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('projects.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-agregar">Actualizar Proyecto</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleInputDisable(checkboxId, inputId) {
            var checkbox = document.getElementById(checkboxId);
            var input = document.getElementById(inputId);
            
            // Estado inicial
            if (checkbox.checked) {
                input.disabled = true;
                input.removeAttribute('required');
                input.removeAttribute('name');
            }
            
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    input.value = '';
                    input.disabled = true;
                    input.removeAttribute('required');
                    input.removeAttribute('name');
                    input.classList.remove('is-invalid');
                } else {
                    input.disabled = false;
                    input.setAttribute('required', 'required');
                    input.setAttribute('name', 'rfc');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleInputDisable('noRfcCheck', 'rfc');

            document.querySelector('form').addEventListener('submit', function(event) {
                var requiredInputs = ['name', 'company_name', 'address', 'phone_number', 'email', 'client_name'];
                var allValid = true;

                for (var i = 0; i < requiredInputs.length; i++) {
                    var input = document.getElementById(requiredInputs[i]);
                    if (input.value === '' && !input.disabled) {
                        input.classList.add('is-invalid');
                        allValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                }

                // Validar RFC si no está deshabilitado
                var rfcInput = document.getElementById('rfc');
                if (!rfcInput.disabled && rfcInput.value === '') {
                    rfcInput.classList.add('is-invalid');
                    allValid = false;
                }

                if (!allValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                this.classList.add('was-validated');
            });

            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('input', function () {
                    if (this.value !== '') {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });
            });
        });
    </script>
</body>
</html>
@endsection