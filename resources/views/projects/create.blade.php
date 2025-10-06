@extends('layouts.app')

@section('title', 'Crear Proyecto')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
<div class="container employee-form-container">
@push('scripts')
 
<script>
// JavaScript específico para proyectos
document.addEventListener('DOMContentLoaded', function() {
    const projectForm = document.querySelector('form');
    if (projectForm) {
        projectForm.addEventListener('submit', function(event) {
            const requiredInputs = ['name', 'company_name', 'address', 'phone_number', 'email', 'client_name'];
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

            // Validar RFC si no está deshabilitado
            const rfcInput = document.getElementById('rfc');
            const noRfcCheck = document.getElementById('noRfcCheck');
            
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
    }
});
</script>
@endpush

@section('content')
<div class="container employee-form-container">
    <h1 class="form-title">Crear Proyecto</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('projects.store') }}" novalidate>
        @csrf
        
        <div class="form-section">
            <h5 class="form-section-title">Información del Proyecto</h5>
            <div class="form-table">
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Descripción:</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h5 class="form-section-title">Información de la Empresa</h5>
            <div class="form-table">
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Nombre de la Empresa:</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-cell">
                        <label class="form-label">RFC:</label>
                        <input type="text" class="form-control @error('rfc') is-invalid @enderror" id="rfc" name="rfc" value="{{ old('rfc') }}" maxlength="13">
                        @error('rfc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="checkbox-group">
                            <input type="checkbox" class="form-check-input" id="noRfcCheck" name="noRfcCheck">
                            <label for="noRfcCheck" class="form-check-label">Sin RFC</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Dirección:</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Ubicación:</label>
                        <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" maxlength="100">
                        @error('ubicacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Teléfono:</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Email:</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Nombre del Cliente:</label>
                        <input type="text" class="form-control @error('client_name') is-invalid @enderror" id="client_name" name="client_name" value="{{ old('client_name') }}" required>
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
            <button type="submit" class="btn btn-agregar">Crear Proyecto</button>
        </div>
    </form>
</div>
@endsection