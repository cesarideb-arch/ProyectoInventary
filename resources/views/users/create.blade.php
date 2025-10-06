@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/forms.css') }}" rel="stylesheet">
<div class="container employee-form-container">
    <h1 class="form-title">Nuevo Empleado</h1>
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        
        <div class="form-section">
            <h5 class="form-section-title">Información del Empleado</h5>
            <div class="form-table">
                <div class="form-row">
                    <div class="form-cell">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-cell">
                        <label class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('password', this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h5 class="form-section-title">Tipo de empleado</h5>
            <div class="form-group">
                <label class="form-label">Rol</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="">Selecciona un rol</option>
                    <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Administrador</option>
                    <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Trabajador</option>
                </select>
            </div>
        </div>
        
        <div class="form-section">
            <h5 class="form-section-title">Verificación de Administrador</h5>
            <div class="form-group">
                <label class="form-label">Contraseña de Administrador</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                    <div class="input-group-append">
                        <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('admin_password', this)">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-agregar">Agregar</button>
        </div>
    </form>
</div>
@endsection