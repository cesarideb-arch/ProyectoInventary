@extends('layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/unified-styles.css') }}" rel="stylesheet">
<div class="container employee-form-container">
    <h1 class="form-title">Crear Categoría</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('categories.store') }}" novalidate>
        @csrf
        
        <div class="form-section">
            <h5 class="form-section-title">Información de la Categoría</h5>
            
            {{--
                ⚠️ CAMBIO IMPORTANTE: Reemplazamos .form-table, .form-row y .form-cell
                con el contenedor principal .form-fields-container para activar el CSS Grid de dos columnas.
            --}}
            <div class="form-fields-container">
                
                {{-- CAMPO 1: Nombre (Ocupa una celda) --}}
                <div class="form-cell">
                    <label for="name" class="field-label">Nombre:</label>
                    <div class="field-input">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                {{-- CAMPO 2: Celda vacía para balancear la fila si Nombre es el único en la primera "fila" --}}
                <div class="form-cell">
                    {{-- Dejar vacío o poner un campo corto si es necesario --}}
                </div>
                
                {{-- CAMPO 3: Descripción (Ocupa una celda) --}}
                <div class="form-cell">
                    <label for="description" class="field-label">Descripción:</label>
                    <div class="field-input">
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" maxlength="500" rows="4">{{ old('description') }}</textarea>
                        <div class="char-counter text-muted">0/500 caracteres</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                {{-- CAMPO 4: Materiales (Ocupa la celda a la derecha de Descripción) --}}
                <div class="form-cell">
                    <label for="materials" class="field-label">Materiales:</label>
                    <div class="field-input">
                        <textarea id="materials" name="materials" class="form-control @error('materials') is-invalid @enderror" maxlength="500" rows="4" placeholder="Ej: algodón, poliéster, lino">{{ old('materials') }}</textarea>
                        <div class="char-counter text-muted">0/500 caracteres</div>
                        @error('materials')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div> {{-- Cierre de .form-fields-container --}}
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-agregar">Crear Categoría</button>
        </div>
    </form>
</div>
@endsection