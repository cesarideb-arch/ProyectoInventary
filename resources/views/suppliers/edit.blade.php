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
        <h1 class="form-title">Editar Proveedor</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('suppliers.update', $supplier['id']) }}" novalidate>
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h5 class="form-section-title">Información del Proveedor</h5>
                <div class="form-table">
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Empresa:</label>
                            <input type="text" id="company" name="company" value="{{ $supplier['company'] }}" required class="form-control @error('company') is-invalid @enderror">
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Teléfono:</label>
                            <input type="text" id="phone" name="phone" value="{{ $supplier['phone'] }}" maxlength="10" required class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="form-label">Email:</label>
                            <input type="email" id="email" name="email" value="{{ $supplier['email'] }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noEmailCheck" name="noEmailCheck" {{ $supplier['email'] == null ? 'checked' : '' }}>
                                <label for="noEmailCheck" class="form-check-label">Sin Email</label>
                            </div>
                        </div>
                        <div class="form-cell">
                            <label class="form-label">Dirección:</label>
                            <input type="text" id="address" name="address" value="{{ $supplier['address'] }}" class="form-control @error('address') is-invalid @enderror">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="checkbox-group">
                                <input type="checkbox" class="form-check-input" id="noAddressCheck" name="noAddressCheck" {{ $supplier['address'] == null ? 'checked' : '' }}>
                                <label for="noAddressCheck" class="form-check-label">Sin dirección</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-agregar">Actualizar</button>
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
            }
            
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    input.value = '';
                    input.disabled = true;
                    input.removeAttribute('required');
                    input.classList.remove('is-invalid');
                } else {
                    input.disabled = false;
                    input.setAttribute('required', 'required');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleInputDisable('noEmailCheck', 'email');
            toggleInputDisable('noAddressCheck', 'address');

            document.querySelector('form').addEventListener('submit', function(event) {
                var requiredInputs = ['company', 'phone'];
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

                // Validar email si no está deshabilitado
                var emailInput = document.getElementById('email');
                if (!emailInput.disabled && emailInput.value === '') {
                    emailInput.classList.add('is-invalid');
                    allValid = false;
                }

                // Validar dirección si no está deshabilitado
                var addressInput = document.getElementById('address');
                if (!addressInput.disabled && addressInput.value === '') {
                    addressInput.classList.add('is-invalid');
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