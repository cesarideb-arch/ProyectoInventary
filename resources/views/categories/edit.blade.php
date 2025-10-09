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
    </head>
<body>
    <div class="container">
        <h1>Editar Categoría</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('categories.update', $category['id']) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ $category['name'] }}" required maxlength="100" class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" maxlength="500" rows="4">{{ $category['description'] ?? '' }}</textarea>
                <div class="char-counter">{{ strlen($category['description'] ?? '') }}/500 caracteres</div>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="materials">Materiales:</label>
                <textarea id="materials" name="materials" class="form-control @error('materials') is-invalid @enderror" maxlength="500" rows="4" placeholder="Ej: algodón, poliéster, lino">{{ $category['materials'] ?? '' }}</textarea>
                <div class="char-counter">{{ strlen($category['materials'] ?? '') }}/500 caracteres</div>
                @error('materials')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var requiredInputs = ['name'];
            var allValid = true;

            for (var i = 0; i < requiredInputs.length; i++) {
                var input = document.getElementById(requiredInputs[i]);
                if (input.value === '' || input.value.length > 100) {
                    input.classList.add('is-invalid');
                    allValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            }

            var descriptionInput = document.getElementById('description');
            if (descriptionInput.value.length > 500) {
                descriptionInput.classList.add('is-invalid');
                allValid = false;
            } else {
                descriptionInput.classList.remove('is-invalid');
            }

            var materialsInput = document.getElementById('materials');
            if (materialsInput.value.length > 500) {
                materialsInput.classList.add('is-invalid');
                allValid = false;
            } else {
                materialsInput.classList.remove('is-invalid');
            }

            if (!allValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.classList.add('was-validated');
        });

        // Función para actualizar contador de caracteres
        function updateCharCounter(textarea) {
            var charCount = textarea.value.length;
            var maxLength = 500;
            var counter = textarea.nextElementSibling;
            
            if (counter && counter.classList.contains('char-counter')) {
                counter.textContent = charCount + '/' + maxLength + ' caracteres';
                
                if (charCount > maxLength) {
                    counter.classList.add('text-danger');
                    counter.classList.remove('text-muted');
                } else {
                    counter.classList.remove('text-danger');
                    counter.classList.add('text-muted');
                }
            }
        }

        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function () {
                if (this.value.length <= 500) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
                updateCharCounter(this);
            });
            
            // Inicializar contador de caracteres
            updateCharCounter(textarea);
        });

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function () {
                if (this.value !== '' && this.value.length <= this.maxLength) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });
    </script>
</body>
</html>
@endsection