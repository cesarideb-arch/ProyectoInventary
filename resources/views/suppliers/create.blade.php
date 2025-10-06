@extends('layouts.app')

@section('title', 'Crear Proveedor')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
<div class="container employee-form-container">
@push('scripts')

<script>
// JavaScript específico para proveedores
document.addEventListener('DOMContentLoaded', function() {
    const supplierForm = document.querySelector('form');
    if (supplierForm) {
        supplierForm.addEventListener('submit', function(event) {
            const requiredInputs = ['company', 'phone'];
            let allValid = true;

            for (let i = 0; i < requiredInputs.length; i++) {
                const input = document.getElementById(requiredInputs[i]);
                if (input.value === '' && !input.disabled) {
                    input.classList.add('is-invalid');
                    allValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            }

            // Validar email si no está deshabilitado
            const emailInput = document.getElementById('email');
            if (!emailInput.disabled && emailInput.value === '') {
                emailInput.classList.add('is-invalid');
                allValid = false;
            }

            // Validar dirección si no está deshabilitado
            const addressInput = document.getElementById('address');
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
    }
});
</script>
@endpush

@section('content')
<div class="container employee-form-container">
    <h1 class="form-title">Crear Proveedor</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('suppliers.store') }}" novalidate>
        @csrf
        
        <div class="form-section">
            <h5 class="form-section-title">Información del Proveedor</h5>
            <div class="form-table">
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Empresa:</label>
                        <input type="text" id="company" name="company" value="{{ old('company') }}" required class="form-control @error('company') is-invalid @enderror">
                        @error('company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Teléfono:</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="10" required class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="checkbox-group">
                            <input type="checkbox" class="form-check-input" id="noEmailCheck" name="noEmailCheck">
                            <label for="noEmailCheck" class="form-check-label">Sin Email</label>
                        </div>
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Dirección:</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="checkbox-group">
                            <input type="checkbox" class="form-check-input" id="noAddressCheck" name="noAddressCheck">
                            <label for="noAddressCheck" class="form-check-label">Sin dirección</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-agregar">Guardar</button>
        </div>
    </form>
</div>
@endsection